<?php

namespace App\Http\Controllers\Business;

use App\FastChargingPrice;
use App\ChargerConnectorType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FastChargingPricesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
            FastChargingPrice::create($request -> all());
        }

        return redirect() -> back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FastChargingPrice $fastChargingPrice)
    {
        $user = Auth::user();
        
        $fastChargingPrice -> load(['chargerConnectorType.charger']);

        if ($fastChargingPrice -> chargerConnectorType -> charger -> company_id == $user -> company_id)
        {
            $fastChargingPrice -> delete();
        }

        return redirect() -> back();
    }
}

