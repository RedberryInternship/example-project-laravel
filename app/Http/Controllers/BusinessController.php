<?php

namespace App\Http\Controllers;

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
        $this -> middleware('business.auth')
              -> except(['getLogin', 'getForgotPassword']);
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
}
