<?php

namespace App\Http\Controllers\Business;

use Auth;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * BusinessController Constructor. 
     */
    public function __construct()
    {
        $this -> middleware('business.auth')
              -> except(['login', 'auth']);
    }

    /**
     * Login Page.
     * 
     * @return view
     */
    public function login()
    {
        return view('business.login') -> with([
            'tabTitle'            => 'ავტორიზაცია',
            'activeMenuItem'      => 'login',
            'backgroundClassName' => 'login'
        ]);
    }

    /**
     * Authenticate Business User.
     * 
     * @param Request $request
     * 
     * @return redirect
     */
    public function auth(Request $request)
    {
        $this -> validate($request, [
            'email'    => 'required',
            'password' => 'required'
        ]);

        $email    = $request -> get('email');
        $password = $request -> get('password');
        
        $user     = User::where('email', $email) -> first();

        if ($user && Hash::check($password, $user -> password))
        {
            Auth::login($user);

            return redirect('/business/charger-groups');
        }

        return redirect() -> back();
    }

    /**
     * Logout Business User from Admin Panel.
     * 
     * @return redirect
     */
    public function logout()
    {
        Auth::logout();

        return redirect('/business/login');
    }
}
