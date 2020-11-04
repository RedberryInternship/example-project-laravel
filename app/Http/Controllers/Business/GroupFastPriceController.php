<?php

namespace App\Http\Controllers\Business;

use App\Group;
use App\FastChargingPrice;
use App\Library\Entities\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Business\Chargers\AddFastPrice;

class GroupFastPriceController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $groupID
     * @return Response
     */
    public function show($groupID)
    {
        $user   = auth() -> user();
        $group  = Group :: with('chargers.charger_connector_types.fast_charging_prices') -> find( $groupID );
        
        return view('business.groups.fast-prices.edit')
            -> with(
                [
                    'group'         => $group,
                    'user'          => $user,
                    'companyName'   => $user -> company -> name,
                    'dayTimesRange' => Helper :: dayTimesRange(),
                ]
            );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AddFastPrice  $request
     * @param  int  $id
     * @return Response
     */
    public function update(AddFastPrice $request, $groupID)
    {
        $user  = auth() -> user();
        $group = Group::with('chargers.charger_connector_types.connector_type') -> find($groupID);

        if ($user -> id != $group -> user_id)
        {
            return redirect() -> back();
        }

        $group 
            -> chargers 
            -> each( function( $charger ) {
                $charger
                    -> charger_connector_types 
                    -> each( function( $chargerConnectorType )  {
                        if( $chargerConnectorType -> isActive() && $chargerConnectorType -> isChargerFast() )
                        {
                            FastChargingPrice::create(
                                [
                                    'charger_connector_type_id' => $chargerConnectorType -> id,
                                    'start_minutes'             => request() -> get('start_minutes'),
                                    'end_minutes'               => request() -> get('end_minutes'),
                                    'price'                     => request() -> get('price'),
                                ]
                            );
                        }
                    });
            });

        return redirect('/business/groups/' . $groupID . '/edit' );
    }
}
