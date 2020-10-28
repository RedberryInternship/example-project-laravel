<?php

namespace App\Http\Controllers\Business;

use Auth;
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
     * @return \Illuminate\Http\Response
     */
    public function show($groupID)
    {
        $group = Group::with([
            'chargers.charger_connector_types.fast_charging_prices'
        ])->find($groupID);

        return view('business.group-fast-prices.edit')->with([
            'group' => $group
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $groupID)
    {
        $user  = Auth::user();
        //todo Vobi, აქ ისე პრიდაპირ არ შეგიძლია where გაუკეთო და გრუპით და იუზერით გაფილტრო პირდაპირ, ქვემოთ კიდევ რომ არ მოგიწიოს შემოწმება ??
        //ასევე დასზღევ ვა არ ჭირდება იქენბა და საერთოდ არ დაბრუნდა გრუპა??
        $group = Group::with('chargers.charger_connector_types.connector_type') -> find($groupID);

        $startMinutes = $request -> get('start_minutes');
        $endMinutes   = $request -> get('end_minutes');
        $price        = $request -> get('price');

        if ($user -> id != $group -> user_id)
        {
            return redirect() -> back();
        }

        //todo Vobi, split that
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
