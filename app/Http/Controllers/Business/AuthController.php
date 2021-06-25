<?php

namespace App\Http\Controllers\Business;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Enums\Role as RoleEnum;

class AuthController extends Controller
{
    /**
     * Login Page.
     *
     * @return view
     */
    public function login()
    {
        return view('business.auth.login') -> with([
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

        if(!$user || $user->role->name !== RoleEnum::BUSINESS)
        {
            return back()->withErrors(
                [
                    __('business.login.login-error-incorrect-credentials'),
                ]
            );
        }

        if(!Hash::check($password, $user -> password))
        {
            return back()->withErrors(
                [
                    __('business.login.login-error-incorrect-credentials'),
                ]
            );
        }

        Auth::login($user);
        return redirect('/business');

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
