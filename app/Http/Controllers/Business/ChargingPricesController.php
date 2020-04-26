<?php

namespace App\Http\Controllers\Business;

use Auth;
use App\ChargingPrice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        ChargingPrice::create($request -> all());

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
     * @param  ChargingPrice  $chargingPrice
     * @return \Illuminate\Http\Response
     */
    public function destroy(ChargingPrice $chargingPrice)
    {
        $user = Auth::user();

        $chargingPrice -> load(['chargerConnectorType.charger']);

        if ($chargingPrice -> chargerConnectorType -> charger -> user_id == $user -> id)
        {
            $chargingPrice -> delete();
        }

        return redirect() -> back();
    }
}

