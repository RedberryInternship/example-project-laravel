<?php

namespace App\Http\Controllers\Business;

use App\FastChargingPrice;
use App\ChargerConnectorType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Business\Chargers\AddFastPrice;

class FastChargingPricesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(AddFastPrice $request)
    {
        $user = auth() -> user();

        $chargerConnectorType = ChargerConnectorType::with('charger') -> find($request -> get('charger_connector_type_id'));

        if ($chargerConnectorType -> charger -> company_id == $user -> company_id)
        {
            FastChargingPrice::create($request -> all());
        }

        return redirect() -> back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(FastChargingPrice $fastChargingPrice)
    {
        $user = auth() -> user();
        
        $fastChargingPrice -> load('charger_connector_type.charger');

        if ($fastChargingPrice -> charger_connector_type -> charger -> company_id == $user -> company_id)
        {
            $fastChargingPrice -> delete();
        }

        return redirect() -> back();
    }
}

