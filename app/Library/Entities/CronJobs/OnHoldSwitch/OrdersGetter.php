<?php

namespace App\Library\Entities\CronJobs\OnHoldSwitch;

use App\Order;
use App\Enums\OrderStatus as OrderStatusEnum;

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
    return Order :: with( 'kilowatt' ) 
      -> whereIn( 'charging_status', self :: activeOrdersStatuses() )
      -> get()
      -> filter(function( $order ) {
        $kilowattLastUpdate = $order -> kilowatt -> updated_at;
        return $kilowattLastUpdate -> diffInMinutes(now()) > self :: $minutes;
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