<?php

namespace App\Library\Entities\CronJobs\OrdersOnPenalty;

use App\Enums\OrderStatus as OrderStatusEnum;

class OrdersEditor
{
  /**
   * Update orders.
   * 
   * @param  Collection $orders
   * @return void
   */
  public static function update( $orders )
  {
    if( $orders )
    {
      $orders -> each( function( $order ) {
        $order -> shouldGoToPenalty() && $order -> updateChargingStatus( OrderStatusEnum :: ON_FINE); 
      });
    }
  }
}