<?php

namespace App\Http\Controllers\Business;

use Auth;
use App\Charger;
use App\ChargerGroup;
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
        $user         = Auth::user();

        $remove       = $request -> get('remove');
        $charger      = Charger::find($request -> get('charger-id'));
        $chargerGroup = ChargerGroup::find($request -> get('charger-group-id'));

        if ($charger -> user_id != $user -> id || $chargerGroup -> user_id != $user -> id)
        {
            return redirect() -> back();
        }

        $charger -> update([
            'charger_group_id' => isset($remove) ? null : $chargerGroup -> id
        ]);

        return redirect() -> back();
    }
}
