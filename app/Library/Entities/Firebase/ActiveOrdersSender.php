<?php

namespace App\Library\Entities\Firebase;

use App\Http\Resources\Order as OrderResource;
use App\Library\Adapters\FCM;
use App\User;

class ActiveOrdersSender
{
  /**
   * Send active orders to app.
   * 
   * @param int $user_id
   */
  public static function send( $userId )
  {
    $user   = User :: with([
      'active_orders.charger_connector_type.charger',
      'active_orders.charger_connector_type.connector_type',
    ]) -> find( $userId );

    $userFirebaseToken = $user -> firebase_token;

    if( $userFirebaseToken )
    {  
      $ordersData = OrderResource :: collection( $user -> active_orders ) -> resolve();
      FCM :: send( $userFirebaseToken, [ 'data' => $ordersData ]);
    }
  }
}