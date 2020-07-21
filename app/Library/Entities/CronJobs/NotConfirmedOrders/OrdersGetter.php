<?php

namespace App\Library\Entities\CronJobs\NotConfirmedOrders;

use App\Enums\OrderStatus as OrderStatusEnum;
use App\Order;

class OrdersGetter
{
  /**
   * Get not confirmed orders.
   * 
   * @return Collection
   */
  public static function get()
  {
    return Order :: where( 'charging_status', OrderStatusEnum :: NOT_CONFIRMED ) -> get();
  }
}