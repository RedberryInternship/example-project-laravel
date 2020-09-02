<?php

namespace App\Library\Entities\CronJobs\OnHoldSwitch;

use App\Enums\OrderStatus as OrderStatusEnum;

class OrdersOnHolder
{
  /**
   * Hold the orders!
   * 
   * @param \Collection|null $orders
   * @return void
   */
  public static function execute( $orders ): void
  {
    $orders && $orders -> each( function( $order ) {
      # $order -> updateChargingStatus( OrderStatusEnum :: ON_HOLD );
    });
    
    $orders && \Log :: info( 'SHOULD BE ON_HOLD ORDERS COUNT - ' . $orders -> count() );
  }
}