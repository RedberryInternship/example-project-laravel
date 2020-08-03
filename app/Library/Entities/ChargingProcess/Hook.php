<?php

namespace App\Library\Entities\ChargingProcess;

use App\Enums\OrderStatus as OrderStatusEnum;

class Hook
{
  /** 
   * Set charging status change dates initial value 
   * when creating.
   * USED IN MODEL HOOKS.
   * 
   * @param \App\Order $order
   * @return void
   */
  public static function setChargingStatusInitialDates( $order ) 
  {
      $availableOrderStatuses = OrderStatusEnum :: getConstantsValues();
      $initialStatuses        = [];

      $now = app() -> runningUnitTests() ? now() -> timestamp : microtime( true );

      foreach( $availableOrderStatuses as $status )
      {
          $initialStatuses [ $status ] = null;
      }

      $order -> charging_status_change_dates = $initialStatuses;
  }


  /**
   * Set charging status change dates if not set,
   * when updating.
   * USED IN MODEL HOOKS.
   */
  public static function updateChargingStatusChangeDates( $order )
  {
      $now = app() -> runningUnitTests() ? now() -> timestamp : microtime( true );

      $chargingStatus                 = $order -> charging_status;
      $orderChargingStatusChargeDates = $order -> charging_status_change_dates; 
    
      $isSet = @ $orderChargingStatusChargeDates [ $chargingStatus ];
      if( ! $isSet )
      {
          $orderChargingStatusChargeDates [ $chargingStatus ] = $now;
          $order -> charging_status_change_dates = $orderChargingStatusChargeDates;
      }
  }
}