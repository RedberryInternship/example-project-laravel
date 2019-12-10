<?php

namespace App\Http\Controllers;
use App\Charger;
// use App\ChargingPrice;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function getTest($charger_id)
    {
    	$charger = Charger::where('id', $charger_id) -> first();
    	foreach($charger -> charger_types as $charger_type)
    	{
    		echo $charger_type -> id;
    	}	
    	exit;
    	dd($charger -> prices);
    	// $charging_price = ChargingPrice::where('charger_id', $charger_id) -> get();
    	// dd($charging_price);
    }
}
