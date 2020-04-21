<?php

namespace App\Http\Controllers;

use App\Facades\Charger;
use App\Facades\Simulator;

 class TestController extends Controller 
 {

	
    public function __invoke()
    {
      
    }

    public function all()
    {
      return response() -> json(
        Charger::all(),
      );
    }

    public function find( $charger_id )
    {
      return response() -> json(
        Charger::find( $charger_id ),
      );
    } 

    public function start( $charger_id, $connector_id )
    {
      return response() -> json(
        Charger::start( $charger_id, $connector_id ),
      );
    }

    public function stop( $charger_id, $transactionID )
    {
      return response() -> json(
        Charger::stop( $charger_id, $transactionID ),
      );
    }

    public function transactionInfo( $transactionID )
    {
      return response() -> json(
        Charger::transactionInfo( $transactionID ),
      );
    }
    
    public function switchChargerIntoLvl2( $charger_id )
    {
      return response() -> json(
        Simulator::activateSimulatorMode( $charger_id ),
      );
    }

    public function bringChargerOnline( $charger_id )
    {
      return response() -> json(
        Simulator::upAndRunning( $charger_id ),
      );
    }

    public function plugOffChargerConnectorCable( $charger_id )
    {
      return response() -> json(
        Simulator::plugOffCable( $charger_id ),
      );
    }

    public function shutdown( $charger_id )
    {
      return response() -> json(
        Simulator::shutdown( $charger_id ),
      );
    }
 }