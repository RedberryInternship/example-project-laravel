<?php

namespace App\Library\Entities\CronJobs\OnHoldSwitch;

use App\Order;
use App\Enums\OrderStatus as OrderStatusEnum;
use App\Library\Entities\ChargingProcess\Timestamp;

class OrdersGetter
{
  /**
   * Get those orders that has not
   * updated for more then x minutes.
   * 
   * @return \Collection
   */
  public static function get()
  {
    return Order :: whereIn( 'charging_status', self :: activeOrdersStatuses() )
      -> filter(function( $order ) {
        $startTime = Timestamp :: build( $order ) -> getStartTimestamp();

        return $startTime && ($startTime -> diffInMinutes(now()) > self :: $minutes );
      });
  }

  /**
   * Active orders statuses.
   * 
   * @return array
   */
  private static function activeOrdersStatuses(): array
  {
    return [
      OrderStatusEnum :: INITIATED  => OrderStatusEnum :: INITIATED,
      OrderStatusEnum :: CHARGING   => OrderStatusEnum :: CHARGING,
    ];
  }

  /**
   * Number of minutes after which order
   * should be switch to ON_HOLD.
   */
  private static $minutes = 2;
}