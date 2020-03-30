<?php

namespace App\Http\Controllers\Business;

use App\Charger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Resources\Charger as ChargerResource;

class ChargerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user     = Auth::user();
        $chargers = Charger::where('user_id', $user -> id) -> with('charger_group') -> orderBy('id', 'DESC') -> get();

        return view('business.chargers.index') -> with([
            'tabTitle'       => 'დამტენები',
            'activeMenuItem' => 'chargers',
            'chargers'       => $chargers,
            'user'           => $user
        ]);
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
        //
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
        $user = Auth::user();
        //$charger = Charger::where('id', $id) -> first();
        $charger = new ChargerResource(Charger::where('id',$id)->with([
            'tags' , 
            'connector_types', 
            'charger_types',
            'charging_prices',
            'fast_charging_prices'
        ]) -> first());

        return view('business.chargers.edit') -> with([
            'tabTitle'       => 'რედაქტირება',
            'activeMenuItem' => 'charger',
            'charger'        => $charger,
            'user'           => $user
        ]);
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
    public function destroy($id)
    {
        //
    }
}
