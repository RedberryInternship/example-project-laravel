<?php

namespace App\Http\Controllers;

use App\Charger;
use App\ChargerGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Charger as ChargerResource;

class BusinessController extends Controller
{
    /**
     * BusinessController Constructor. 
     */
    public function __construct()
    {
        $this -> middleware('auth') -> except(['getLogin', 'getForgotPassword']);
    }

    public function getIndex()
    {
        $user = Auth::user();

        return view('business.dashboard')-> with([
            'tabTitle'       => 'მთავარი გვერდი',
            'activeMenuItem' => 'dashboard',
            'user'           => $user
        ]);
    }

    public function getLogin()
    {
        return view('business.login') -> with([
            'tabTitle'            => 'ავტორიზაცია',
            'activeMenuItem'      => 'login',
            'backgroundClassName' => 'login'
        ]);
    }

    public function getForgotPassword()
    {
         return view('business.forgot-password') -> with([
            'tabTitle'            => 'პაროლის აღდგენა',
            'activeMenuItem'      => 'forgot_password',
            'backgroundClassName' => 'forgot'
        ]);       
    }

    public function getChargerGroups()
    {
        $user          = Auth::user();
        $chargerGroups = ChargerGroup::where('user_id', $user -> id) -> with('chargers') -> orderBy('id', 'DESC') -> get();

        return view('business.charger-groups') -> with([
            'tabTitle'       => 'დამტენების ჯგუფები',
            'activeMenuItem' => 'chargers',
            'chargerGroups'  => $chargerGroups,
            'user'           => $user
        ]);
    }

    public function getChargers()
    {
        $user     = Auth::user();
        $chargers = Charger::where('user_id', $user -> id) -> with('charger_group') -> orderBy('id', 'DESC') -> get();

        return view('business.chargers') -> with([
            'tabTitle'       => 'დამტენები',
            'activeMenuItem' => 'chargers',
            'chargers'       => $chargers,
            'user'           => $user
        ]);
    }

    public function getChargerEdit($charger_id)
    {
        $user    = Auth::user();
        //$charger = Charger::where('id', $charger_id) -> first();
        $charger = new ChargerResource(Charger::where('id',$charger_id)->with([
            'tags' , 
            'connector_types', 
            'charger_types',
            'charging_prices',
            'fast_charging_prices'
        ]) -> first());

        return view('business.charger-edit') -> with([
            'tabTitle'       => 'რედაქტირება',
            'activeMenuItem' => 'charger',
            'charger'        => $charger,
            'user'           => $user
        ]);
    }
}
