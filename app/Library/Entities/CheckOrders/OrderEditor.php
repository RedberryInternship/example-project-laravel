<?php

namespace App\Library\Entities\CheckOrders;

use App\Enums\OrderStatus as OrderStatusEnum;
use App\Order;

class OrderEditor
{
  /**
   * Update order if exists.
   * 
   * @param  Order|null $order
   * @return void
   */
  public static function updateIfExists( $order ): void
  {
    if( ! $order )
    {
      return;
    }

    if( $order -> charger_connector_type -> isChargerFast() )
    {
      $order -> updateChargingStatus( OrderStatusEnum :: CHARGING );
    }
    else
    {
      $order -> updateChargingStatus( OrderStatusEnum :: INITIATED );
    }
  }
}