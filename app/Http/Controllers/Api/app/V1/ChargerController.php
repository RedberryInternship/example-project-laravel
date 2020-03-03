<?php
namespace App\Http\Controllers\Api\app\V1;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Charger;
use App\Http\Resources\ChargerCollection;
use App\Http\Resources\Charger as ChargerResource;

class ChargerController extends Controller
{
    /**
     * Get chargers.
     * 
     * @param Request $request
     * @param Charger $charger
     */
    public function getChargers(Request $request, Charger $charger)
    {
        $filters = $request -> get('filters');

        $charger = $charger -> active();

        if (isset($filters) && isset($filters['free']))
        {
            $charger = $charger -> filterByFreeOrNot($filters['free']);
        }

        if (isset($filters) && isset($filters['type']))
        {
            $charger = $charger -> filterByType($filters['type']);
        }

        if (isset($filters) && isset($filters['public']))
        {
            $charger = $charger -> filterByPublicOrNot($filters['public']);
        }
            
        return new ChargerCollection(
            $charger
                -> OrderBy('id', 'desc')
                -> withAllAttributes()
                -> get()
        );
    }

    /**
     * Get Single Charger.
     * 
     * @param Charger $charger
     * @param $charger_id
     */
    public function getSingleCharger(Charger $charger, $charger_id)
    {
        return new ChargerResource(
            $charger
                -> withAllAttributes()
                -> find($charger_id)
        );
    }
}
