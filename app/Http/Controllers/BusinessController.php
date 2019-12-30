<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function getIndex(){
        // $user   = Auth::user();
        // // if(!$user)
        // // {
        // //     return redirect('/login');
        // // }
        return view('business.dashboard')-> with([
            'tabTitle'       => 'მთავარი გვერდი',
            'activeMenuItem' => 'dashboard'
        ]);
    }

    public function getLogin(){
        return view('business.login') -> with([
            'tabTitle'            => 'ავტორიზაცია',
            'activeMenuItem'      => 'login',
            'backgroundClassName' => 'login'
        ]);
    }
    
    public function getRegister(){
        return view('business.register') -> with([
            'tabTitle'            => 'რეგისტრაცია',
            'activeMenuItem'      => 'register',
            'backgroundClassName' => 'register'
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
