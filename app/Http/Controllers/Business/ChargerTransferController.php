<?php

namespace App\Http\Controllers\Business;

use App\Group;
use App\Charger;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class ChargerTransferController extends Controller
{
    /**
     * Transfer Charger to Group.
     *
     * @param $request
     */
    public function __invoke(Request $request)
    {
        $request->validate(
            [
                'remove'=> 'nullable',
                'group-id' => 'required',
                'charger-id' => 'required',
            ]
        );

        
        $remove  = $request -> get('remove');
        $group   = Group::with('user')->findOrFail($request -> get('group-id'));
        $charger = Charger::findOrFail($request -> get('charger-id'));

        // dd($group, $charger);
        $this->transferGate($group, $charger);

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

    /**
     * Transfer gate.
     * 
     * @param Group $groupId
     * @param Charger $chargerId
     * @return void
     */
    private function transferGate(Group &$group,Charger &$charger)
    {
        $invalidGroup = (int) auth()->user()->company_id !== (int) $group->user->company_id;
        $invalidCharger = (int) auth()->user()->company_id !== (int) $charger->company_id;
        abort_if($invalidGroup || $invalidCharger, Response::HTTP_FORBIDDEN);
    }
}
