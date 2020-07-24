<?php

namespace App\Library\Entities\CronJobs\NotConfirmedOrders;

use App\Enums\OrderStatus as OrderStatusEnum;

class OrdersEditor
{
  /**
   * Update not confirmed order charging status to ON_HOLD,
   * if we have not received feedback from Real Chargers Back-End.
   * 
   * @param \Collection
   * @return void
   */
  public static function update( $orders )
  {
    if( ! $orders )
    {
      return;
    }

    foreach( $orders as $order )
    {
      $order -> updateChargingStatus( OrderStatusEnum :: ON_HOLD );
    }
  }

}