<?php

namespace App\Http\Controllers;

use App\Facades\Charger;
use App\Facades\Simulator;

class TestController extends Controller 
{

	
    public function index()
    {
      
      dd( Simulator::plugOffCable( 29 ))  ;
    }

  }
