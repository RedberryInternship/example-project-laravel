<?php

namespace App\Http\Controllers\Business;

use App\User;
use App\Charger;
use App\BusinessService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\ChargerConnectorType;
use App\Library\Entities\Helper;
use App\Http\Controllers\Controller;
use App\Enums\ChargerType as ChargerTypeEnum;
use App\Enums\ConnectorType as ConnectorTypeEnum;

class ChargerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $userId   = auth() -> user() -> id;
        $user     = User :: with( 'company' ) -> find( $userId );

        $chargers = Charger::where('company_id', $user -> company_id)
                        -> whereNotNull('company_id')
                        -> with('groups')
                        -> orderBy('id', 'DESC')
                        -> get();

        return view('business.chargers.index') -> with([
            'tabTitle'       => 'დამტენები',
            'activeMenuItem' => 'chargers',
            'chargers'       => $chargers,
            'user'           => $user,
            'companyName'    => $user -> company -> name,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $user    = auth() -> user();
        $charger = Charger::where('id', $id) 
            -> with(
                [
                    'business_services',
                    'groups',
                ]
            ) 
            -> firstOrFail();

        $chargerBusinessServices = $charger -> business_services -> pluck('id') -> toArray();
        $chargerConnectorTypes   = ChargerConnectorType :: active()
            -> with(
                [ 
                    'connector_type', 
                    'charging_prices', 
                    'fast_charging_prices'
                ]
            )
            -> where('charger_id', $id)
            -> get();
            
        return view('business.chargers.edit') 
            -> with(
                [
                    'chargerBusinessServices' => $chargerBusinessServices,
                    'businessServices'        => BusinessService::all(),
                    'chargerConnectorTypes'   => $chargerConnectorTypes,
                    'languages'               => Helper :: allLang(),
                    'tabTitle'                => 'რედაქტირება',
                    'activeMenuItem'          => 'chargers',
                    'charger'                 => $charger,
                    'user'                    => $user,
                    'companyName'             => $user -> company -> name,
                    'dayTimesRange'           => Helper :: dayTimesRange(),
                ]
            );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, Charger $charger)
    {
        $user = auth() -> user();

        if ($user -> company_id != $charger -> company_id)
        {
            return redirect() -> back();
        }
        $charger -> setTranslations('name', $request -> get('names'))
                 -> setTranslations('description', $request -> get('descriptions'))
                 -> setTranslations('location', $request -> get('locations'))
                 -> save();

        $charger -> business_services() -> sync($request -> get('charger_business_services'));

        return redirect() -> back();
    }

    /**
     * Get filtered chargers for modal on dashboards.
     * 
     * @return JSON
     */
    public function getFilteredChargers()
    {
        $companyId = auth() -> user() -> company_id;
        $chargerStatus = request() -> get('status');
        $chargerType = request() -> get('type');

        $connectorTypes = $chargerType === ChargerTypeEnum :: FAST
            ? [ ConnectorTypeEnum :: COMBO_2, ConnectorTypeEnum :: CHADEMO ]
            : [ ConnectorTypeEnum :: TYPE_2 ];

        return Charger :: with('charger_connector_types.connector_type') 
            -> whereCompanyId($companyId)
            -> whereStatus($chargerStatus)
            -> whereHas('charger_connector_types.connector_type', function( $query ) use( $connectorTypes ) {
                return $query -> whereIn('name', $connectorTypes );
            }) -> get();
    }
}
