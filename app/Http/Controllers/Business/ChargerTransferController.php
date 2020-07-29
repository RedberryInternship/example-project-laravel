<?php

namespace App\Http\Controllers\Business;

use Auth;
use App\Group;
use App\Charger;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChargerTransferController extends Controller
{
    /**
     * ChargerTransferController Constructor.
     */
    public function __construct()
    {
        $this -> middleware('business.auth');
    }

    /**
     * Transfer Charger to Group.
     *
     * @param $request
     */
    public function __invoke(Request $request)
    {
        $user    = Auth::user();

        $remove  = $request -> get('remove');

        $group   = Group::find($request -> get('group-id'));
        $charger = Charger::find($request -> get('charger-id'));

        if ($charger -> company_id != $user -> company_id || $group -> user_id != $user -> id)
        {
            return redirect() -> back();
        }

        if ($remove)
        {
            $charger -> groups() -> detach($group -> id);
        }
        else
        {
            $charger -> groups() -> attach($group -> id);
        }

        return redirect() -> back();
    }
}
