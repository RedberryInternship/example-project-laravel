<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Facades\Simulator;
use App\Facades\Charger;

use App\Traits\Message;

use App\Order;
use App\Library\Adapters\FCM;
use App\Http\Resources\Order as OrderResource;

class TestController extends Controller 
{
  use Message;
    
  public function __invoke()
  { 
    $orders   = Order         :: latest() -> take( 2 ) -> get();
    $resource = OrderResource :: collection( $orders ) -> resolve();

    FCM :: send( 'dly3r7JJg5LEiM-M106C4t:APA91bGaxoev9Dcjb8ErKR_2JHkOtOjsYLUhjcLNAh3MIAjFTKDWC_VsBzV1zBZRizx7dU-QdmqCym0JD1x0vWs2r-bfcVIwFipjTTZji6bVG3nmttQJyOaVpPknK70l5qOjQ9HKaMu0', [ 'data' => $resource ] );
  }

  public function firebase()
  {
    return view( 'firebase' );
  }

  public function disconnect( Request $request )
  { 
    if( request() -> has( 'chargerCode' ) )
    {
      $chargerId    = '0000';
      $chargerCode  = request() -> get( 'chargerCode' );
      $charger      = DB :: table( 'chargers' ) -> where( 'code', $chargerCode ) -> first();

      if( $charger )
      {
        $chargerId = $charger -> charger_id;
      }

      return response() -> json(
        Simulator :: plugOffCable( $chargerId ),
      );
    }

    return view('simulator.disconnect');
  }
  
  private function memory()
  {
    return memory_get_usage() / 1024 / 1024;
  }


  private function get_json_data($data_type)
  {
    $path = public_path () . "/jsons". "/" . $data_type . ".json";
    $json = json_decode(file_get_contents($path));
    $data = $json -> RECORDS;
    
    return collect( $data );
  }

  public function all()
  {
    return response() -> json(
      Charger :: all(),
    );
  }

  public function find( $charger_id )
  {
    return response() -> json(
      Charger :: find( $charger_id ),
    );
  } 

  public function start( $charger_id, $connector_id )
  {
    return response() -> json(
      Charger :: start( $charger_id, $connector_id ),
    );
  }

  public function stop( $charger_id, $transactionID )
  {
    return response() -> json(
      Charger :: stop( $charger_id, $transactionID ),
    );
  }

  public function transactionInfo( $transactionID )
  {
    return response() -> json(
      Charger :: transactionInfo( $transactionID ),
    );
  }
  
  public function switchChargerIntoLvl2( $charger_id )
  {
    return response() -> json(
      Simulator :: activateSimulatorMode( $charger_id ),
    );
  }

  public function bringChargerOnline( $charger_id )
  {
    return response() -> json(
      Simulator :: upAndRunning( $charger_id ),
    );
  }

  public function plugOffChargerConnectorCable( $charger_id )
  {
    return response() -> json(
      Simulator :: plugOffCable( $charger_id ),
    );
  }

  public function shutdown( $charger_id )
  {
    return response() -> json(
      Simulator :: shutdown( $charger_id ),
    );
  }
}