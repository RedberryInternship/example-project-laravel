<?php
namespace App\Http\Controllers\Api\app\V1;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Charger;
use App\Library\Charger as ChargerLibrary;

class ChargerController extends Controller
{
    public function getChargers(ChargerLibrary $chargerLibrary)
    {
    	$chargers = Charger::OrderBy('id', 'desc') -> get();
        return response() -> json([
            'charger' => $chargerLibrary -> getChargerArray($chargers)
        ], 200);
    }

    public function getSingleCharger(ChargerLibrary $chargerLibrary, $charger_id)
    {
    	$chargers = Charger::where('id', $charger_id) -> get();
        return response() -> json([
            'charger' => $chargerLibrary -> getChargerArray($chargers)
        ], 200);
    }
}
