<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\User;

class UserController extends Controller
{
    public function postRegister(Request $request)
    {   
        $first_name = $request -> get('first_name');
        $last_name = $request -> get('last_name');
        $phone_number = $request -> get('phone_number');
        $email = $request -> get('email');
        $password1 = $request -> get('password1');
        $password2 = $request -> get('password2');
        //dd($first_name);
        //dd($email . " " . $first_name . " " . $last_name . " " . $phone_number . " " . $password1);
        $check_user = User::where('phone_number', $phone_number)->first();
        if(!$check_user)
        {
            echo "OK";
        } else {
            echo "ARSEBOBS";
        }
        // if($password1 == $password2)
        // {
        //     //$check_user = 
        // }
    }
}
