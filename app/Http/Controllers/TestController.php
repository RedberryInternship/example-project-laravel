<?php

namespace App\Http\Controllers;

use App\Facades\Charger;
use App\Facades\Simulator;

use Illuminate\Http\Request;

class TestController extends Controller 
{

	
	public function index(){

      dd(Simulator::disconnect(2));
      dd(Simulator::remove(1));
      dd(Simulator::add(1));
      dd(Simulator::activateSimulatorMode(1));

      dd(Charger::transactionInfo(2));
      dd(Charger::stop(1,2));
      dd(Charger::start(1,2));
      dd(Charger::all());
      dd(Charger::find(1));
      
      exit();
    }
  }
