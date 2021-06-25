<?php

namespace App\Http\Controllers\Business;

use App\User;
use App\ChargingPrice;
use App\ChargerConnectorType;
use Illuminate\Http\Response;
use App\Library\Entities\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Business\Chargers\AddLvl2Price;
use App\Http\Requests\Business\Chargers\EditLvl2Price;

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

    /**
     * Return edit single charging price view.
     */
    public function edit($id)
    {
        $chargingPrice = ChargingPrice :: find($id);
        $userId   = auth() -> user() -> id;
        $user     = User :: with( 'company' ) -> find( $userId );

        return view('business.chargers.connector-types.edit-lvl2')->with([
            'chargingPrice'     => $chargingPrice,
            'dayTimesRange'     => Helper :: dayTimesRange(),
            'tabTitle'          => __('business.sidebar.chargers'),
            'activeMenuItem'    => 'chargers',
            'user'              => $user,
            'companyName'       => $user -> company -> name,
        ]);
    }

    /**
     * Update single charging price record.
     */
    public function update(EditLvl2Price $request, $id)
    {
        $data = $request->validated();
        $chargingPrice = ChargingPrice :: find($id);
        $chargingPrice -> update($data);
        $charger = $chargingPrice -> charger_connector_type -> charger;

        return redirect() -> route('business-chargers.edit', $charger -> id);
    }
}

