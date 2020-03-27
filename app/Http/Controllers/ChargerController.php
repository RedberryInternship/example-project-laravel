<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Charger;
use App\Http\Resources\Charger as ChargerResource;
use App\BusinessService;
use App\ChargerBusinessService;

class ChargerController extends Controller
{
    public function getChargers()
    {
        $user     = Auth::user();
        $chargers = Charger::where('user_id', $user -> id) -> OrderBy('id', 'desc') -> get();
        //$chargers = Charger::where('user_id', $user -> id) -> get();
        return view('business.chargers') -> with([
            'tabTitle'            => 'დამტენები',
            'activeMenuItem'      => 'chargers',
            'chargers'            => $chargers,
            'user'                => $user    
        ]);
    }

    public function getChargerServices($charger_id)
    {
        $user   = Auth::user();

        $charger = new ChargerResource(Charger::where('id',$charger_id)->with([
            'tags' , 
            'connector_types', 
            'charger_types',
            'charging_prices',
            'fast_charging_prices'
        ]) -> first());

        $charger_business_service 		    = ChargerBusinessService::where('charger_id', $charger -> id) 
        	-> get();
        
        $charger_business_service_ids_array  = $charger_business_service -> pluck('business_service_id') -> toArray();

        $business_services 					= BusinessService::where('user_id', $user -> id) 
        	-> WhereNotIn('id', array_values($charger_business_service_ids_array))
         	-> OrderBy('id', 'desc') 
         	-> get();

        return view('business.charger-services')->with([
            'tabTitle'         		   => 'სერვისები',
            'activeMenuItem'   		   => 'charger',
            'charger'          		   => $charger,
            'user'             		   => $user,
            'business_services' 	   => $business_services,
            'charger_business_service' => $charger_business_service
        ]);
    }

    public function postChargerBusinessService(Request $request)
    {
    	ChargerBusinessService::create([
    		'charger_id' 			=> $request->input('charger_id'),
    		'business_service_id'	=> $request->input('business_service_id'),
    	]);
    	return back();
    }

    public function getDeleteChargerBusinessService($charger_business_service_id)
    {
    	ChargerBusinessService::where('id', $charger_business_service_id) -> delete();	
    	return back();
    }

}
