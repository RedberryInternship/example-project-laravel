<?php

namespace App\Http\Controllers;

use App\Facades\Charger;
use App\Facades\Simulator;

use Illuminate\Http\Request;
use GuzzleHttp\Client;


class TestController extends Controller 
{

	
	  public function index(){


      var_dump(
        config('espace.mishas_back_protocol')
      );

      // set up
      // var_dump(
      //   Simulator::activateSimulatorMode(29),
      //   Simulator::upAndRunning(29),
      // );


      // start charging
      // var_dump(
      //   Charger::start(29,1),
      // );


      // get transaction info
      // var_dump(
      //   Charger::transactionInfo(74447),
      // );

    
      // // plug cable off the charger
      // var_dump(
      //   Simulator::plugOffCable(29),
      // );



      // stop charging [from app]
      // var_dump(
      //   Charger::stop(29, 74447),
      // );


      // shutdown charger
      // var_dump(
      //   Simulator::shutdown(29),
      // );
    }

  }
