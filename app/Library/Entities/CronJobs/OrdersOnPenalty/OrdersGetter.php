<?php

namespace App\Library\Entities\CronJobs\OrdersOnPenalty;

use App\Enums\OrderStatus as OrderStatusEnum;

use App\Order;

class OrdersGetter
{
  /**
   * Get charged and used up orders.
   * 
   * @return Collection
   */
  public static function get()
  {
    return Order :: whereIn( 'charging_status', 
      [ 
        OrderStatusEnum :: CHARGED, 
        OrderStatusEnum :: USED_UP, 
      ]
    ) -> get();
  }
}