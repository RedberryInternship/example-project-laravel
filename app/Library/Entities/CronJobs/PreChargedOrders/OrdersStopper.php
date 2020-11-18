<?php

namespace App\Library\Entities\CronJobs\PreChargedOrders;

use App\Enums\OrderStatus as OrderStatusEnum;
use Illuminate\Support\Facades\Log;
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
    $orders = Order :: with( 'charger_connector_type.charger' ) -> whereIn( 'id', $orderIds ) -> get();

    if( $orders )
    {
      foreach( $orders as $order )
      {
        $chargerId      = $order -> getCharger() -> charger_id;
        $transactionId  = $order -> charger_transaction_id;
        $order -> updateChargingStatus( OrderStatusEnum :: CHARGED );
        
        self :: log( $order );
        Charger :: stop( $chargerId, $transactionId );
      }
    }
  }

  /**
   * log.
   * 
   * @param Order $order
   * @return void
   */
  private static function log( Order $order )
  {
    Log :: channel( 'pre-charged' ) -> info('Already Charged - Order ID '. $order -> id . ' | Transaction ID - '. $order -> charger_transaction_id );
  }
}