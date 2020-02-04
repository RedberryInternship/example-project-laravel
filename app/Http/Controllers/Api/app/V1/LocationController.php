<?php

namespace App\Http\Controllers\api\app\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PulkitJalan\GeoIP\GeoIP;

class LocationController extends Controller
{
	public function getLocation(Request $request)
	{
	   	$geoip = new GeoIP();
    	$city = $geoip->getCity();	
    	return response() -> json(['city' => $city]);
	}
}
