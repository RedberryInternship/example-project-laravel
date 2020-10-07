<?php

namespace App\Http\Controllers\Business;

use App\ChargingPrice;
use App\ChargerConnectorType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $chargerConnectorType = ChargerConnectorType::with('charger') -> find($request -> get('charger_connector_type_id'));

        if ($chargerConnectorType -> charger -> company_id == $user -> company_id)
        {
            ChargingPrice::create($request -> all());
        }

        return redirect() -> back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  ChargingPrice  $chargingPrice
     * @return \Illuminate\Http\Response
     */
    public function destroy(ChargingPrice $chargingPrice)
    {
        $user = Auth::user();

        $chargingPrice -> load(['chargerConnectorType.charger']);

        if ($chargingPrice -> chargerConnectorType -> charger -> company_id == $user -> company_id)
        {
            $chargingPrice -> delete();
        }

        return redirect() -> back();
    }
}

