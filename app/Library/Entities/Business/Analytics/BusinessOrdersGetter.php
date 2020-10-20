<?php

namespace App\Library\Entities\Business\Analytics;

use App\Order;
use App\Enums\OrderStatus as OrderStatusEnum;

class BusinessOrdersGetter
{
  /**
   * Get business order.
   * 
   * @return \Collection
   */
  public static function get()
  {
    $year = request() -> year;
    ! $year && $year = now() -> year;

    $user = auth() -> user();

    return Order::with(['payments', 'charger_connector_type.charger'])
        -> where('company_id', $user -> company_id) 
        -> where('charging_status', OrderStatusEnum :: FINISHED) 
        -> whereYear('created_at', '=', $year) 
        -> get();
  }
}