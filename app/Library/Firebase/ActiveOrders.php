<?php

namespace App\Library\Firebase;

use App\Library\Adapters\FCM;
use App\Http\Resources\Order as OrderResource;

class ActiveOrders
{
  /**
   * Send active orders to App.
   * 
   * @param  string|null  $userFirebaseToken
   * @param  array        $activeOrders
   * @return void
   */
  public static function send( $userFirebaseToken, $activeOrders )
  {
    if( $userFirebaseToken )
    {
      $ordersData = OrderResource :: collection( $activeOrders ) -> resolve();
      FCM :: send( $userFirebaseToken, [ 'data' => $ordersData ]);
    }
  }
}