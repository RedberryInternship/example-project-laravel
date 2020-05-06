<?php

namespace App\Http\Controllers\Business;

use Auth;
use App\user;
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

            return redirect('/business');
        }

        return redirect() -> back();
    }
}
