<?php

namespace App\Http\Controllers\Business;

use Auth;
use App\FastChargingPrice;
use App\ChargerConnectorType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

        if ($chargerConnectorType -> charger -> user -> id == $user -> id)
        {
            FastChargingPrice::create($request -> all());
        }

        return redirect() -> back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

        if ($fastChargingPrice -> chargerConnectorType -> charger -> user_id == $user -> id)
        {
            $fastChargingPrice -> delete();
        }

        return redirect() -> back();
    }
}

