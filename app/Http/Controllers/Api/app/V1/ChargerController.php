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
                -> get() 
                -> filter(function( $charger ) use( $user ) {
            
                    /**
                     * if charger is not hidden show it to the user.
                     */
                    if(! $charger -> hidden) 
                    {
                        return true;
                    }
        
                    /**
                     * If user is not authenticated and 
                     * charger is hidden.
                     */
                    if(! $user )
                    {
                        return false;
                    }
        
                    /**
                     * If user is authenticated and charger is hidden
                     * with user's phone_number in its whitelist, 
                     * in that case show charger.
                     */
                    foreach( $charger -> whitelist as $allowedMember )
                    {
                        if($allowedMember -> phone === $user -> phone_number) 
                        {
                            return true;
                        }
                    }
        
                    /**
                     * if user is authenticated and his/her phone number is not
                     * in charger's whitelist, then hide charger from that user.
                     */
                    return false;
                });

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
