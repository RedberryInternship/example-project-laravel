<?php

namespace App\Http\Controllers\Api\app\V1;

use App\Http\Controllers\Controller;

use App\Traits\Message;

use App\Http\Requests\StartCharging;
use App\Http\Requests\StopCharging;

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
    $request -> startChargingProcess();
    $order   = $request -> createOrder();

    if( $request -> isChargerFast() )
    {
      $request -> pay();
    }

    $request -> createKilowattRecord();

    return new OrderResource( $order );
  }

  /**
   * Route method for stop charging 
   * call to Misha's back.
   * 
   * @param   StopCharging $request
   * @return  JsonResponse
   */
  public function stop(StopCharging $request)
  {
    $request -> stopChargingProcess();
    $request -> updateChargingStatus();

    $resource = $request -> buildResource();
    
    return $resource;
  }
}