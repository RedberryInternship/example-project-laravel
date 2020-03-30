<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
}
