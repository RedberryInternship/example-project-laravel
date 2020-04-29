<?php

use App\Charger;
use App\ChargerGroup;
use App\ChargingPrice;
use App\ChargerConnectorType;
use Redberry\Library\ChargerPrices\ChargerPrices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

Route::get('/charger-groups', function (Request $request) {
	$groups = ChargerGroup::all();

	return [
		'groups' => $groups
	];
});

Route::get('/chargers', function (Request $request, Charger $charger) {
	$group 	  = $request -> get('group');

	$chargers = $charger -> filterBy('charger_group_id', [$group])
						 -> with('connector_types')
						 -> get();

	return [
		'chargers' => $chargers
	];
});

Route::post('/save-min-max', function (Request $request, ChargerConnectorType $chargerConnectorType, ChargerPrices $chargerPrices) {
	$minPrice = $request -> get('minPrice');
	$maxPrice = $request -> get('maxPrice');
	$chargers = $request -> get('chargers');

	$connectorTypes = $chargerPrices -> getChargersConnectorTypes($chargers);

	$chargerConnectorTypes = $chargerConnectorType -> whereIn('id', $connectorTypes);

	if (isset($minPrice) && $minPrice != '')
	{
		$chargerConnectorTypes -> update([
			'min_price' => $minPrice
		]);
	}
	
	if (isset($maxPrice) && $maxPrice != '')
	{
		$chargerConnectorTypes -> update([
			'max_price' => $maxPrice
		]);
	}

	return response() -> json(true, 200);
});

Route::post('/save-level2', function (Request $request, ChargerConnectorType $chargerConnectorType, ChargerPrices $chargerPrices) {
	$minKwt    = $request -> get('minKwt');
	$maxKwt    = $request -> get('maxKwt');
	$startTime = $request -> get('startTime');
	$endTime   = $request -> get('endTime');
	$price     = $request -> get('price');
	$chargers  = $request -> get('chargers');

	$connectorTypes = $chargerPrices -> getChargersConnectorTypes($chargers);

	$chargerConnectorTypes = $chargerConnectorType -> whereIn('id', $connectorTypes) -> get();

	$queryRaws = [];
	$timeNow   = date('Y-m-d H:i:s');
	foreach ($chargerConnectorTypes as $chargerConnectorType)
	{
		$queryRaws[] = [
			'min_kwt' 					=> $minKwt,
			'max_kwt' 					=> $maxKwt,
			'start_time' 				=> $startTime,
			'end_time' 					=> $endTime,
			'price' 					=> $price,
			'charger_connector_type_id' => $chargerConnectorType -> id,
			'created_at' 				=> $timeNow,
			'updated_at' 				=> $timeNow
		];
	}

	ChargingPrice::insert($queryRaws);

	return response() -> json(true, 200);
});

