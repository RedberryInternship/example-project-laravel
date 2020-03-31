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
    public function getChargers(Request $request, Charger $chargerModel)
    {
        $user    = auth('api') -> user();
        $charger = $chargerModel -> active();

        if ($request -> has('free'))
        {
            $charger = $charger -> filterByFreeOrNot($request -> get('free'));
        }

        if ($request -> has('type'))
        {
            $charger = $charger -> filterByType($request -> get('type'));
        }

        if ($request -> has('public'))
        {
            $charger = $charger -> filterByPublicOrNot($request -> get('public'));
        }

        if ($request -> has('businessID'))
        {
            $charger = $charger -> filterByBusiness($request -> get('businessID'));
        }

        if ($request -> has('text'))
        {
            $charger = $charger -> filterByText($request -> get('text'));
        }

        $charger  = $charger -> groupedChargersWithSibblingChargers();

        $chargers = $charger
                -> OrderBy('id', 'desc')
                -> withAllAttributes()
                -> get();

        if ($user)
        {
            $favoriteChargers = $user -> favorites -> pluck('id') -> toArray();

            $chargerModel -> addFilterAttributeToChargers($chargers, $favoriteChargers);
        }

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
        return new ChargerResource(
            $charger
                -> withAllAttributes()
                -> find($charger_id)
        );
    }
}
