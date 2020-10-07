<?php

namespace App\Http\Controllers\Business;

use App\Group;
use App\ChargingPrice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class GroupPriceController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $groupID
     * @return \Illuminate\Http\Response
     */
    public function show($groupID)
    {
        $group = Group::with([
            'chargers.charger_connector_types.charging_prices',
        ]) -> find($groupID);

        return view('business.group-prices.edit') -> with([
            'group' => $group
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $groupID
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $groupID)
    {
        $user  = Auth::user();
        $group = Group::with('chargers.charger_connector_types.connector_type') -> find($groupID);

        $minKwt    = $request -> get('min_kwt');
        $maxKwt    = $request -> get('max_kwt');
        $startTime = $request -> get('start_time');
        $endTime   = $request -> get('end_time');
        $price     = $request -> get('price');

        if ($user -> id != $group -> user_id)
        {
            return redirect() -> back();
        }

        foreach ($group -> chargers as $charger)
        {
            foreach ($charger -> charger_connector_types as $chargerConnectorType)
            {
                if (in_array(strtolower($chargerConnectorType -> connector_type -> name), ['type 2']))
                {
                    ChargingPrice::create([
                        'charger_connector_type_id' => $chargerConnectorType->id,
                        'min_kwt'                   => $minKwt,
                        'max_kwt'                   => $maxKwt,
                        'start_time'                => $startTime,
                        'end_time'                  => $endTime,
                        'price'                     => $price
                    ]);
                }
            }
        }

        return redirect('/business/groups/' . $groupID . '/edit' );
    }
}
