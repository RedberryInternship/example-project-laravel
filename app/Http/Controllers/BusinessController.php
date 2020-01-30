<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Charger;
use App\Http\Resources\Charger as ChargerResource;

class BusinessController extends Controller
{
    public function getIndex(){
        $user   = Auth::user();
        if(!$user)
        {
            return redirect('/business/login');
        }
        return view('business.dashboard')-> with([
            'tabTitle'       => 'მთავარი გვერდი',
            'activeMenuItem' => 'dashboard',
            'user'           => $user
        ]);
    }

    public function getLogin(){
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

    public function getChargers()
    {
        $user     = Auth::user();
        $chargers = Charger::OrderBy('id', 'desc') -> get();
        //$chargers = Charger::where('user_id', $user -> id) -> get();
        return view('business.chargers') -> with([
            'tabTitle'            => 'დამტენები',
            'activeMenuItem'      => 'chargers',
            'chargers'            => $chargers,
            'user'                => $user    
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
        return view('business.charger-edit')->with([
            'tabTitle'       => 'რედაქტირება',
            'activeMenuItem' => 'charger',
            'charger'        => $charger,
            'user'           => $user
        ]);
    }
}
