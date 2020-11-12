<?php

namespace App\Http\Controllers\Business;

use App\ChargingPrice;
use App\ChargerConnectorType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Business\Chargers\AddLvl2Price;

class ChargingPricesController extends Controller
{
    /**
     * ChargingPricesController Constructor. 
     */
    public function __construct()
    {
        $this -> middleware('business.auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(AddLvl2Price $request)
    {
        $user = auth() -> user();

        $chargerConnectorType = ChargerConnectorType :: with('charger') -> find($request -> get('charger_connector_type_id'));

        if ($chargerConnectorType -> charger -> company_id == $user -> company_id)
        {
            ChargingPrice :: create($request -> all());
        }

        return redirect() -> back();
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

        if ($chargingPrice -> charger_connector_type -> charger -> company_id == $user -> company_id)
        {
            $chargingPrice -> delete();
        }

        return redirect() -> back();
    }
}

