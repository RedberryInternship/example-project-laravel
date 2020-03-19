<?php

namespace App\Http\Controllers;

use App\Facades\Charger;
use App\Facades\Simulator;

use Illuminate\Http\Request;

class TestController extends Controller 
{

	
	public function index(){

      // dd(Simulator::plugOffCable(29));
      // dd(Simulator::upAndRunning(29));
      // dd(Simulator::shutDown(29));
      // dd(Simulator::activateSimulatorMode(29));

      // dd(Charger::transactionInfo(2));
      // dd(Charger::stop(1,2));
      // dd(Charger::start(29,1));
      // dd(Charger::all());
      // dd(Charger::find(29));
      
      exit();
    }

    public function charger($id){
      dd(Charger::find($id)['body']['data']);
    }

    public function allCharger(){
      return Charger::all();
    }
  }
