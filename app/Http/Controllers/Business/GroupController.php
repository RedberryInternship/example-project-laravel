<?php

namespace App\Http\Controllers\Business;

use App\Group;
use App\Charger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user   = Auth::user();
        $groups = Group::where('user_id', $user -> id)
                      -> with('chargers')
                      -> orderBy('id', 'DESC')
                      -> get();

        return view('business.groups.index') -> with([
            'tabTitle'       => 'დამტენების ჯგუფები',
            'activeMenuItem' => 'groups',
            'groups'         => $groups,
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
        $user = Auth::user();

        $request -> merge([
            'user_id' => $user -> id
        ]);

        if ($request -> has('name') && $request -> get('name'))
        {   
            Group::create($request -> all());
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
    public function edit(Group $group)
    {
        $user = Auth::user();
        
        if ($group -> user_id != $user -> id)
        {
            return redirect() -> back();
        }

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

        return view('business.groups.edit') -> with([
            'user'            => $user,
            'group'           => $group,
            'groupChargerIds' => $groupChargerIds,
            'activeMenuItem'  => 'groups'
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
     * @param  ChargerGroup  $chargerGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        Charger::where(['charger_group_id' => $chargerGroup -> id]) -> update([
            'charger_group_id' => null
        ]);

        $chargerGroup -> delete();

        return redirect() -> back();
    }
}

