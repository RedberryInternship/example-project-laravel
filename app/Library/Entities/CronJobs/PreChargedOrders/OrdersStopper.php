<?php

namespace App\Library\Entities\CronJobs\PreChargedOrders;

use App\Enums\OrderStatus as OrderStatusEnum;
use App\Facades\Charger;
use App\Order;

class OrdersStopper
{
  /**
   * Stop orders because they are 
   * already charged.
   * 
   * @param  Order $order
   * @return void
   */
  public static function stop( $orderIds ): void
  {
    $orders = Order :: whereIn( 'id', $orderIds ) -> get();

    if( $orders )
    {
      foreach( $orders as $order )
      {
        $chargerId      = $order -> charger_connector_type -> charger -> charger_id;
        $transactionId  = $order -> charger_transaction_id;
        $order -> updateChargingStatus( OrderStatusEnum :: CHARGED );
        Charger :: stop( $chargerId, $transactionId );
      }
    }
  }
}