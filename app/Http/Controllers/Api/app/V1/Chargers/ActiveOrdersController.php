<?php

namespace App\Http\Controllers\Api\App\V1\Chargers;

use App\Http\Controllers\Controller;

use App\Http\Resources\ActiveOrder;

use App\ChargerConnectorType;
use App\User;
use Illuminate\Http\Resources\Json\Resource;

class ActiveOrdersController extends Controller
{

   public function __construct()
   {
     Resource :: withoutWrapping();
   }

  /**
  * Return active orders.
  *
  * @return Illuminate\Http\JsonResponse
  */
  public function get()
  {
    $userId = auth() -> user() -> id;
    
    $user   = User :: with([
      'active_orders.charger_connector_type.charger',
      'active_orders.charger_connector_type.connector_type',
    ]) -> find( $userId );
    
    return ActiveOrder :: collection( $user -> active_orders );
  }
}