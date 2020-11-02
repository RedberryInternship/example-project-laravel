<?php

namespace App\Http\Controllers\Business;

use App\Http\Requests\Business\Groups\RemoveGroupChargingPrices;
use App\Http\Requests\Business\Groups\StoreAllChargersIntoGroup;
use App\Http\Requests\Business\Groups\StoreGroup;
use App\Library\Interactors\Business\Groups;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Group;
use App\User;

class GroupController extends Controller
{
    /**
     * ChargerController Constructor. 
     */
    public function __construct()
    {
        $this -> middleware('business.auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $userId  = auth() -> user() -> id;
        $user    = User :: find( $userId );
        $groups  = Group::where('user_id', $user -> id)
                      -> with('chargers')
                      -> orderBy('id', 'DESC')
                      -> get();

        return view('business.groups.index') -> with([
            'tabTitle'       => 'დამტენების ჯგუფები',
            'activeMenuItem' => 'groups',
            'groups'         => $groups,
            'user'           => $user,
            'companyName'    => $user -> company -> name,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return View
     */
    public function store(StoreGroup $request)
    {
        Group::create(
            [
                'name' => $request -> name,
                'user_id' => auth() -> user() -> id,
            ]
        );

        return redirect() -> back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return View
     */
    public function edit(Group $group)
    {
        $userId = auth() -> user() -> id;
        $user   = User :: with( 'company.chargers' ) -> find( $userId );
        $companyChargers = $user -> company -> chargers;

        $group -> load([
            'chargers' => function($query) {
                $query -> orderBy('id', 'DESC');
            }
        ]);

        $user -> load([
            'company.chargers' => function($query) {
                $query -> with('groups')
                       -> orderBy('id', 'DESC');
            }
        ]);

        $groupChargerIds = [];
        foreach ($group -> chargers as $charger)
        {
            $groupChargerIds[] = $charger -> id;
        }

        return view('business.groups.edit') -> with(
            [
                'user'             => $user,
                'group'            => $group,
                'groupChargerIds'  => $groupChargerIds,
                'activeMenuItem'   => 'groups',
                'allChargersAreIn' => $companyChargers -> count() === $group -> chargers -> count(),
                'companyName'      => $user -> company -> name,
            ]
        ); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  ChargerGroup  $chargerGroup
     * @return Response
     */
    public function destroy(Group $group)
    {
        $group -> chargers() -> detach();
        $group -> delete();

        return redirect() -> back();
    }

    /**
     * Remove group charger charging prices.
     * 
     * @return View
     */
    public function deleteChargingPrices(RemoveGroupChargingPrices $request) 
    {
       Groups :: deleteGroupChargersChargingPrices($request -> group_id);
    }

    /**
     * Store all the company chargers 
     * to group chargers.
     * 
     * @param int $groupId
     * @return void
     */
    public static function storeAllChargersToGroup(StoreAllChargersIntoGroup $request)
    {
        Groups :: storeAllCompanyChargersIntoGroup($request -> group_id);
    }
}

