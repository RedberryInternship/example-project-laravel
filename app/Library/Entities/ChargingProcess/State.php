<?php

namespace App\Library\Entities\ChargingProcess;

use App\Library\Entities\ChargingProcess\Timestamp;
use App\Enums\OrderStatus as OrderStatusEnum;

use App\Config;

/**
 * Depends on:
 * @param Timestamp
 * @param Order
 * @param Calculator
 */

trait State
{
  /**
   * KiloWattHour line with which we're gonna
   * determine if charging is officially started
   * and if charging is officially ended.
   */
  private $kiloWattHourLine = .1;
  
  /**
   * Determine if charging is stopped 
   * due to that the car is charged or ether
   * user has used up the money and is in penalty
   * relief mode.
   * 
   * @return bool
   */
  public function enteredPenaltyReliefMode()
  {
    $enteredPenaltyReliefModeTimestamp = Timestamp :: build( $this ) -> getStopChargingTimestamp();
    return !! $enteredPenaltyReliefModeTimestamp;     
  }

  /**
   * Determine if user already used up all the 
   * money he/she typed when charging with BY_AMOUNT.
   * 
   * @return bool
   */
  public function hasAlreadyUsedUpMoney()
  {
    return !! Timestamp :: build( $this ) -> getChargingStatusTimestamp( OrderStatusEnum :: USED_UP );
  }

  /**
   * Determine if user is on penalty.
   * 
   * @return bool
   */
  public function isOnPenalty()
  {
      return !! Timestamp :: build( $this ) -> getPenaltyTimestamp();
  }

  /**
   * Determine if charging has officially started.
   * 
   * @param   float $kiloWattHour
   * @return  bool
   */
  private function chargingHasStarted()
  {
      $chargingPower    = $this  -> getChargingPower();
      $kiloWattHourLine = $this  -> kiloWattHourLine;

      return $chargingPower > $kiloWattHourLine;
  }

  /**
   * Determine if car is charged.
   * 
   * @param \App\Order $order
   * @return bool
   */
  public function carHasAlreadyCharged()
  {
      $chargingPower    = $this  -> getChargingPower();
      $kiloWattHourLine = $this  -> kiloWattHourLine;

      return $chargingPower < $kiloWattHourLine;
  }

  /**
   * Determine if order is on fine.
   * 
   * @param \App\Order $order
   * @return bool
   */
  public function shouldGoToPenalty()
  {
    if( $this -> charger_connector_type -> isChargerFast() )
    {
      return false;
    }

    if(  ! $this -> carHasAlreadyStoppedCharging() )
    {
        return false;
    }

    $config               = Config :: first();
    $penaltyReliefMinutes = $config -> penalty_relief_minutes;

    $chargedTime = Timestamp :: build( $this ) -> getStopChargingTimestamp();

    if( ! $chargedTime )
    {
        return false;
    }

    $elapsedTime         = $chargedTime -> diffInMinutes( now() );

    return $elapsedTime >= $penaltyReliefMinutes;
  }

  /**
   * Determine if car has already stopped charging.
   * 
   * @return bool
   */
  private function carHasAlreadyStoppedCharging()
  {
      return in_array( $this -> charging_status, [ OrderStatusEnum :: CHARGED, OrderStatusEnum :: USED_UP ]);
  }

  /**
   * Determine if order can go to finish status.
   * 
   * @param string|null
   * @return bool
   */
  public function canGoToFinishStatus()
  {
    $finishableStatuses = [
      OrderStatusEnum :: INITIATED ,
      OrderStatusEnum :: CHARGING  ,
      OrderStatusEnum :: CHARGED   ,
      OrderStatusEnum :: USED_UP   ,
      OrderStatusEnum :: ON_FINE   ,
      OrderStatusEnum :: ON_HOLD   ,
    ];

    return in_array( $this -> charging_status, $finishableStatuses );
  }

   /**
   * Determine if consumed money is above 
   * the paid currency.
   * 
   * @return bool
   */
  private function shouldPay()
  {
      $paidMoney      = $this -> countPaidMoney();
      $consumedMoney  = $this -> countConsumedMoney();
      
      return  $consumedMoney > $paidMoney;
  }

  /**
   * Determine if paid money is less 
   * then consumed money.
   * 
   * @return bool
   */
  private function shouldRefund()
  {
      $paidMoney      = $this -> countPaidMoney();
      $consumedMoney  = $this -> countConsumedMoney();
      
      return  $consumedMoney < $paidMoney;
  }
}