<?php
namespace App\Http\Controllers\Api\app\V1;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Charger;
use App\Http\Resources\ChargerCollection;
use App\Library\Charger as ChargerLibrary;
use App\Http\Resources\Charger as ChargerResource;

class ChargerController extends Controller
{
    public function getChargers(ChargerLibrary $chargerLibrary)
    {
        return new ChargerCollection(Charger::OrderBy('id', 'desc') ->with([
            'tags' , 
            'connector_types', 
            'charger_types',
            'charging_prices',
            'fast_charging_prices'
            ]) -> get());
    	// $chargers = Charger::OrderBy('id', 'desc') -> get();
     //    return response() -> json([
     //        'charger' => $chargerLibrary -> getChargerArray($chargers)
     //    ], 200);
    }

    public function getSingleCharger(ChargerLibrary $chargerLibrary, $charger_id)
    {
        return new ChargerResource(Charger::where('id',$charger_id)->with([
            'tags' , 
            'connector_types', 
            'charger_types',
            'charging_prices',
            'fast_charging_prices'
            ]) -> first());
    	// $chargers = Charger::where('id', $charger_id) -> get();
     //    return response() -> json([
     //        'charger' => $chargerLibrary -> getChargerArray($chargers)
     //    ], 200);
    }
}
