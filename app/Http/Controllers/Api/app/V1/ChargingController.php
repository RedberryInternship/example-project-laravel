<?php

namespace App\Http\Controllers\Api\app\V1;

use App\Http\Controllers\Controller;

use App\Traits\Message;

use App\Http\Requests\StartCharging;
use App\Http\Requests\StopCharging;

use App\Library\Interactors\ChargingStarter;
use App\Library\DataStructures\ChargingStarter as ChargingStarterRequest;

use App\Http\Resources\Order as OrderResource;

class ChargingController extends Controller
{
  use Message;

  /**
   * Route method for starting Charging
   * 
   * @param   StartCharging $request
   * @return  JsonResponse
   */
  public function start( StartCharging $request )
  {
    $requestModel = new ChargingStarterRequest;
    $requestModel -> setChargerConnectorTypeId( $request -> charger_connector_type_id );
    $requestModel -> setChargingType          ( $request -> charging_type             );
    $requestModel -> setPrice                 ( $request -> price                     );
    $requestModel -> setUserCardId            ( $request -> user_card_id              );

    $order = ChargingStarter :: prepare( $requestModel ) -> start();

    return new OrderResource( $order );
  }

  /**
   * Route method for stop charging 
   * call to Misha's back.
   * 
   * @param   StopCharging $request
   * @return  JsonResponse
   */
  public function stop( StopCharging $request )
  {
    $request -> stopChargingProcess();
    $request -> updateChargingStatus();

    $resource = $request -> buildResource();
    
    return $resource;
  }
}