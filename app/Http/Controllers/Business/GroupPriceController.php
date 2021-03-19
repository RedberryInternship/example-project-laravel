<?php

namespace App\Http\Controllers\Business;

use App\Group;
use App\ChargingPrice;
use App\Library\Entities\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Business\Chargers\AddLvl2Price;

class GroupPriceController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $groupID
     * @return Response
     */
    public function show($groupID)
    {
        $group  = Group :: with('chargers.charger_connector_types.charging_prices') -> findOrFail( $groupID );
        $user   = auth() -> user();

        return view('business.groups.lvl2-prices.edit') 
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
     * @param  AddLvl2Price $request
     * @param  int  $groupID
     * @return Response
     */
    public function update(AddLvl2Price $request, $groupID)
    {
        $user  = auth() -> user();
        $group = Group :: with('chargers.charger_connector_types.connector_type') -> find($groupID);

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
                    if( $chargerConnectorType -> isActive() && ! $chargerConnectorType -> isChargerFast() )
                    {
                        ChargingPrice::create(
                            [
                                'charger_connector_type_id' => $chargerConnectorType->id,
                                'min_kwt'                   => request() -> get('min_kwt'),
                                'max_kwt'                   => request() -> get('max_kwt'),
                                'start_time'                => request() -> get('start_time'),
                                'end_time'                  => request() -> get('end_time'),
                                'price'                     => request() -> get('price'),
                            ]
                        );
                    }
                });
        });

        return redirect('/business/groups/' . $groupID . '/edit' );
    }
}
