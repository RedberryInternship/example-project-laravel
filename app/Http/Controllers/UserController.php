<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function postAuthenticate(Request $request)
    {
        // $this -> validate($request, [
        //     'phone_number'      => 'required',
        //     'password'          => 'required'
        // ]);

        $phone_number = $request -> get('phone_number');
        $password     = $request -> get('password');
        
        $user     = User::where('phone_number', $phone_number) -> first();  

        if ($user)
        {  
            if (Hash::check($password, $user -> password))
            {
                Auth::login($user);                
                return redirect('/business/');
            }
            else
            {
                return back() -> withErrors(['Incorrect Password']);
            }
        }
        else
        {
            return back() -> withErrors(['No Such User']);
        }
    }
    public function getlogout(Request $request) {
      Auth::logout();
      return redirect('/business/login');
    }
}
