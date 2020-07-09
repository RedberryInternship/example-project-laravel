<?php

namespace App\Library\Entities\ChargingProcess;

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
   * Determine if charging is stopped 
   * due to that the car is charged or ether
   * user has used up the money and is in penalty
   * relief mode.
   * 
   * @return bool
   */
  public function enteredPenaltyReliefMode()
  {
      $enteredPenaltyReliefModeTimestamp = $this -> getStopChargingTimestamp();

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
      return !! $this -> getChargingStatusTimestamp( OrderStatusEnum :: USED_UP );
  }

  /**
   * Determine if user is on penalty.
   * 
   * @return bool
   */
  public function isOnPenalty()
  {
      $isOnPenalty = !! $this -> getPenaltyTimestamp();

      return $isOnPenalty;
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
   * Determine if car has already stopped charging.
   * 
   * @return bool
   */
  public function carHasAlreadyStoppedCharging()
  {
      return  $this -> charging_status == OrderStatusEnum :: CHARGED 
          ||  $this -> charging_status == OrderStatusEnum :: USED_UP ;
  }

  /**
   * Determine if order is on fine.
   * 
   * @param \App\Order $order
   * @return bool
   */
  public function isOnFine()
  {
      $config               = Config :: first();
      $penaltyReliefMinutes = $config -> penalty_relief_minutes;

      $chargedTime = $this -> getStopChargingTimestamp();

      if( ! $chargedTime )
      {
          return false;
      }

      $elapsedTime          = $chargedTime -> diffInMinutes( now() );

      return $elapsedTime >= $penaltyReliefMinutes;
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