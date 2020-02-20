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
    	$latitude  = $geoip->getLatitude();	
    	$longitude = $geoip->getLongitude();	
    	return response() -> json([
    		'Latitude'  => $latitude,
    		'Longitude' => $longitude
    	]);
	}
}
