<?php

use App\Charger;
use App\ChargerGroup;
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
