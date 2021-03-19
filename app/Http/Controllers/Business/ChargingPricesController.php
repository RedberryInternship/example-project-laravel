<?php

namespace App\Http\Controllers\Business;

use App\ChargingPrice;
use App\ChargerConnectorType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Business\Chargers\AddLvl2Price;
use Illuminate\Http\Response;

class ChargingPricesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(AddLvl2Price $request)
    {
        $user = auth() -> user();

        $chargerConnectorType = ChargerConnectorType :: with('charger') -> findOrFail($request -> get('charger_connector_type_id'));

        if ($chargerConnectorType -> charger -> company_id == $user -> company_id)
        {
            ChargingPrice :: create($request -> all());
        }
        else
        {
            abort(Response::HTTP_FORBIDDEN);
        }

        return redirect() -> back(Response::HTTP_CREATED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ChargingPrice  $chargingPrice
     * @return Response
     */
    public function destroy(ChargingPrice $chargingPrice)
    {
        $user = auth() -> user();

        $chargingPrice -> load( 'charger_connector_type.charger' );

        if($chargingPrice -> charger_connector_type -> charger -> company_id == $user -> company_id)
        {
            $chargingPrice -> delete();
        }
        else
        {
            abort(Response::HTTP_FORBIDDEN);
        }

        return redirect() -> back();
    }
}

