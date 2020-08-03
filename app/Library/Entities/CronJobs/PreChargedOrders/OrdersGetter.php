<?php

namespace App\Library\Entities\CronJobs\PreChargedOrders;

use App\Library\Entities\ChargingProcess\Timestamp;
use App\Enums\OrderStatus as OrderStatusEnum;
use App\Order;

class OrdersGetter
{
  /**
   * Allowed minutes to pass. if those minutes
   * is reached then the car is considered 
   * to be fully charged.
   */
  private static $allowedMinutes = 3;

  /**
   * Get pre charged orders.
   * 
   * @return Collection
   */
  public static function get()
  {
    $orders = Order :: with( 'charger_connector_type.charger' ) -> where( 'charging_status', OrderStatusEnum :: INITIATED ) -> get() 
    -> filter( function ( $order ) {
      $timestamp          = Timestamp :: build( $order );
      $initiatedTimestamp = $timestamp -> getInitiatedTimestamp();
      $diffMinutes        = $initiatedTimestamp -> diffInMinutes( now() );

      return $diffMinutes > self :: $allowedMinutes;
    });

    return $orders;
  }
}