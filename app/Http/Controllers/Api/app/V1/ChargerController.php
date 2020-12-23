<?php
namespace App\Http\Controllers\Api\app\V1;

use App\Charger;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChargerCollection;
use App\Enums\ChargerStatus as ChargerStatusEnum;
use App\Http\Resources\Charger as ChargerResource;

class ChargerController extends Controller
{
    /**
     * Get chargers.
     * 
     * @param Request $request
     * @param Charger $charger
     */
    public function getChargers()
    {
        $user = auth('api') -> user();

        $chargers = Charger :: OrderBy('id', 'desc')
                -> where('status', '!=', ChargerStatusEnum :: NOT_PRESENT)
                -> withAllAttributes()
                -> get();

        Charger::addChargingPrices($chargers);
        Charger::addIsFreeAttributes($chargers);
        Charger::addIsFavoriteAttributes($chargers);

        return new ChargerCollection($chargers);
    }

    /**
     * Get Single Charger.
     * 
     * @param Charger $charger
     * @param $charger_id
     */
    public function getSingleCharger(Charger $charger, $charger_id)
    {
        $charger = $charger
            -> withAllAttributes()
            -> find( $charger_id );

        Charger :: addIsFreeAttributes( $charger );

        return new ChargerResource( $charger );
    }
}
