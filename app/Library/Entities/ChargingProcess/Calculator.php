<?php

namespace App\Library\Entities\ChargingProcess;

use App\Exceptions\NoSuchChargingPriceException;

use App\Enums\PaymentType as PaymentTypeEnum;
use App\Enums\ChargerType as ChargerTypeEnum;
use App\Enums\OrderStatus as OrderStatusEnum;

use App\Library\Entities\ChargingProcess\Timestamp;

/**
 * Depends on:
 * @param State
 * @param Timestamp
 */

trait Calculator
{
  /**
   * Count money the user has already paid.
   * 
   * @return  float
   * @example 10.25
   */
  public function countPaidMoney()
  {
      if( ! isset( $this -> payments ))
      {
          $this -> load( 'payments' );
      }

      if( count( $this -> payments ) == 0 )
      {
          return 0.0;
      }
  
      $paidMoney = $this 
          -> payments 
          -> where( 'type', PaymentTypeEnum :: CUT ) 
          -> sum( 'price' );

      $paidMoney = round        ( $paidMoney, 2 );
      
      return $paidMoney;
  }

  /**
   * Count the money user has already consumed(Charged).
   * 
   * @return float
   */
  public function countConsumedMoney()
  {
      $this -> load( 'charger_connector_type' );
      $this -> load( 'payments' );

      if( $this -> hasAlreadyUsedUpMoney() )
      {
          return null;
      }
      
      if( count( $this -> payments ) == 0 )
      {
          return 0.0;
      }
      
      $chargerType = $this -> charger_connector_type -> determineChargerType();
      
      $consumedMoney = $chargerType == ChargerTypeEnum :: FAST 
          ? $this -> countConsumedMoneyByTime()
          : $this -> countConsumedMoneyByKilowatt();
      
      $consumedMoney = round        ( $consumedMoney, 2 );

      return $consumedMoney;
  }

  /**
   * Counting consumed money when charger type is FAST.
   * 
   * @return float
   */
  private function countConsumedMoneyByTime()
  {
      $elapsedMinutes      = $this -> calculateChargingElapsedTimeInMinutes();

      $chargingPriceRanges =  $this 
          -> charger_connector_type
          -> collectFastChargingPriceRanges( $elapsedMinutes );

      $consumedMoney = $this -> accumulateFastChargerConsumedMoney( 
          $chargingPriceRanges, 
          $elapsedMinutes,
        );
      
      return $consumedMoney;
  }

  /**
   * Accumulate fast charger consumed money
   * based on elapsed minutes.
   * 
   * @param Collection $chargingPriceRanges
   * @param int        $elapsedMinutes
   */
  private function accumulateFastChargerConsumedMoney( $chargingPriceRanges, $elapsedMinutes )
  {
      $consumedMoney          = 0;

      $chargingPriceRanges -> each( function ( $chargingPriceInstance ) 
      use ( &$consumedMoney, $elapsedMinutes ) {
          
          $startMinutes       = $chargingPriceInstance -> start_minutes;
          $endMinutes         = $chargingPriceInstance -> end_minutes;
          $price              = $chargingPriceInstance -> price;
          $minutesInterval    = $endMinutes - $startMinutes + 1;

          if( $elapsedMinutes > $chargingPriceInstance -> end_minutes)
          {
              $consumedMoney += $price * $minutesInterval;
          }
          else
          {
              $consumedMoney += ( $elapsedMinutes - $startMinutes + 1 ) * $price;
          }
      });

      return $consumedMoney;
  }

  /**
   * Counting consumed money when charger type is LVL2.
   * 
   * @return float
   */
  private function countConsumedMoneyByKilowatt()
  {
    $timestamp          = Timestamp :: build( $this );
    $chargingPower      = $this -> kilowatt -> getChargingPower();        
    $startChargingTime  = $timestamp -> getChargingStatusTimestamp( OrderStatusEnum :: CHARGING );
    $startChargingTime  = $startChargingTime -> toTimeString();
    $elapsedMinutes     = $timestamp -> calculateChargingElapsedTimeInMinutes();

    $chargingPriceInfo  = $this 
        -> charger_connector_type 
        -> getSpecificChargingPrice( $chargingPower, $startChargingTime );
    
    if( ! $chargingPriceInfo )
    {
        throw new NoSuchChargingPriceException();
    }

    $chargingPrice = $chargingPriceInfo -> price;
    
    return $chargingPrice * $elapsedMinutes;
  }

   /**
   * Count money to refund the user.
   * 
   * @return float
   */
  public function countMoneyToRefund()
  {
      if( count( $this -> payments ) == 0 )
      {
          return 0.0;
      }

      if( $this -> hasAlreadyUsedUpMoney() )
      {
          return null;
      }

      $moneyToRefund = $this -> countPaidMoney() - $this -> countConsumedMoney();
      $moneyToRefund = round( $moneyToRefund, 2 );
  
      return $moneyToRefund;
  }

  /**
   * Count money to cut.
   * 
   * @return float
   */
  public function countMoneyToCut()
  {
      $consumedMoney  = $this -> countConsumedMoney();
      $alreadyPaid    = $this -> countPaidMoney();
      $moneyToCut     = $consumedMoney - $alreadyPaid;
      
      return $moneyToCut;
  }

  /**
   * Count money to refund with penalty fee.
   * 
   * @return float
   */
  public function countPenaltyFee()
  {
    $timestamp              = Timestamp :: build( $this );
    $penaltyTimestamp       = $timestamp -> getPenaltyTimestamp();
    $finishedTimestamp      = $timestamp -> getChargingStatusTimestamp( OrderStatusEnum :: FINISHED );

    if( ! $finishedTimestamp )
    {
        $finishedTimestamp  = now();
    }
    
    $elapsedMinutes         = $penaltyTimestamp -> diffInMinutes( $finishedTimestamp );
    $penaltyPricePerMinute  = $this -> getPenaltyPricePerMinute();
            
    return ( $elapsedMinutes + 1 ) * $penaltyPricePerMinute;    
  }
}