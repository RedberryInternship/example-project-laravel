<?php

namespace App\Library\Entities\CronJobs\NotConfirmedOrders;

use App\Library\Entities\ChargingProcess\Timestamp;
use App\Enums\OrderStatus as OrderStatusEnum;

class OrdersFilter
{
  /**
   * Allowed minutes, after which NOT_CONFIRMED orders should
   * transform into OH_HOLD orders.
   * 
   * @var int $allowedMinutes
   */
  private static $allowedMinutes = 2;

  /**
   * Filter not confirmed by elapsed time.
   * if we orders are not updated more then x minutes then
   * those orders are to changed charging status into
   * ON_HOLD.
   * 
   * @return \Collection
   */
  public static function execute( $orders )
  {
    if( $orders )
    {
      $orders = $orders -> filter( function( $order ) {
        $timestamp             = Timestamp :: build( $order );
        $notConfirmedTimestamp = $timestamp -> getChargingStatusTimestamp( OrderStatusEnum :: NOT_CONFIRMED );
        $elapsedMinutes        = $notConfirmedTimestamp -> diffInMinutes(now());
        
        return $elapsedMinutes > self :: $allowedMinutes;
      });
    }

    return $orders;
  }
}