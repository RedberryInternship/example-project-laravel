<?php

namespace App\Http\Controllers;

use App\Facades\Charger;
use App\Facades\Simulator;

use App\Enums\PaymentType;

use App\Traits\Message;
 
class TestController extends Controller 
 {
  use Message;
     
    public function __invoke()
    {
        $payments = $this -> get_json_data( 'payment' );

        dd(
          $payments -> where( 'actual_price', '!=' , '' ) -> take(10) -> toArray(),
        );
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