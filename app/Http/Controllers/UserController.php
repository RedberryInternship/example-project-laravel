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
    public function postRegister(Request $request)
    {   
        $first_name     = $request -> get('first_name');
        $last_name      = $request -> get('last_name');
        $phone_number   = $request -> get('phone_number');
        $email          = $request -> get('email');
        $password1      = $request -> get('password1');
        $password2      = $request -> get('password2');
        $status_message = '';

        $validator = Validator::make($request->all(), [
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'email'             => 'required|string|email|max:255|unique:users',
            'phone_number'      => 'required|string|unique:users'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $check_user = User::where('phone_number', $phone_number)->first();

        if(!$check_user)
        {
            if($password1 === $password2)
            {
                $user = User::create([
                    'first_name'   => $first_name,
                    'last_name'    => $last_name,
                    'phone_number' => $phone_number,
                    'email'        => $email,
                    'role'         => 3,
                    'active'       => 1,
                    'verified'     => 0,
                    'password'     => Hash::make($password1)
                ]);
                if(!$user){
                    return back() -> withErrors(['Problem creating new user!']);
                }else{
                    Auth::login($user);
                    return redirect('/business/');
                }
            }else{
                return back() -> withErrors(['Passwords does not match!']);
            }
        } else {
            return back() -> withErrors(['User is already exists!']);
        }
    }
}
