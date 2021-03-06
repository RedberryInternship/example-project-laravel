<?php

namespace App\Http\Controllers\Api\app\V1;

use App\Http\Controllers\Controller;

use App\Http\Resources\Order as OrderResource;
use App\Library\Interactors\ChargingUpdater;
use App\Order;
use App\User;

class OrderController extends Controller
{
  /**
   * Set without wrapping mode onto
   * Order resource.
   */
  public function __construct()
  {
    OrderResource :: withoutWrapping();
  }

  /**
  * Return active orders.
  *
  * @return Illuminate\Http\JsonResponse
  */
  public function getActiveOrders()
  {
    $userId = auth() -> user() -> id;
    
    $user   = User :: with([
      'active_orders.charger_connector_type.charger',
      'active_orders.charger_connector_type.connector_type',
    ]) -> find( $userId );

    $user -> active_orders -> each(function( $order ) {
      ChargingUpdater :: updateAndCacheOrder($order);
    });
    
    return OrderResource :: collection( $user -> active_orders );
  }

  /**
   * Return one order.
   * 
   * @param   integer $order_id
   * @return  \App\Http\Resources\Order
   */
  public function get( $order_id )
  {
    $order = Order :: with(
        [
          'charger_connector_type.charger',
          'charger_connector_type.connector_type',
        ]
      ) -> find( $order_id );

    ChargingUpdater :: updateAndCacheOrder($order);

    return new OrderResource( $order );
  }

}