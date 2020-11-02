<?php

namespace App\Http\Controllers\Business;

use App\Group;
use App\FastChargingPrice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
                ]
            );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $groupID)
    {
        $user  = auth() -> user();
        $group = Group::with('chargers.charger_connector_types.connector_type') -> find($groupID);

        $startMinutes = $request -> get('start_minutes');
        $endMinutes   = $request -> get('end_minutes');
        $price        = $request -> get('price');

        if ($user -> id != $group -> user_id)
        {
            return redirect() -> back();
        }

        foreach ($group -> chargers as $charger)
        {
            foreach ($charger -> charger_connector_types as $chargerConnectorType)
            {
                if (in_array(strtolower($chargerConnectorType -> connector_type -> name), ['combo 2', 'chademo']))
                {
                    FastChargingPrice::create([
                        'charger_connector_type_id' => $chargerConnectorType->id,
                        'start_minutes'             => $startMinutes,
                        'end_minutes'               => $endMinutes,
                        'price'                     => $price
                    ]);
                }
            }
        }

        return redirect('/business/groups/' . $groupID . '/edit' );
    }
}
