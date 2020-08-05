<?php

namespace App\Library\Entities\Firebase;

use App\Http\Resources\Order as OrderResource;
use Illuminate\Support\Facades\Log;
use App\Library\Adapters\FCM;
use App\Order;
use App\User;

class FinishNotificationSender
{
  /**
   * Send finish notification with active orders
   * and finished order data.
   * 
   * @param  int $chargerTransactionId
   * @return void
   */
  public static function send( $chargerTransactionId ): void
  {
    $finishedOrder = Order :: where( 'charger_transaction_id', $chargerTransactionId ) -> first();
    $user          = User  :: find( $finishedOrder -> user_id );

    $activeOrder   = $user -> active_orders() -> first();

    $orderIds = [];
    $orderIds []= $finishedOrder -> id;

    if( $activeOrder )
    {
      $orderIds []= $activeOrder -> id;
    }

    $ordersToSend = Order :: whereIn( 'id', $orderIds ) -> get();
    $data         = OrderResource :: collection( $ordersToSend ) -> resolve();
    
    Log :: info([ 'data' => $data ]);

    FCM :: send( $user -> firebase_token, [ 'data' => $data ]);
  }
}