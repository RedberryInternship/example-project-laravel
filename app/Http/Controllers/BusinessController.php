<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function getIndex(){
        return view('business.index');
    }

    public function getLogin(){
        return view('business.login');
    }

    public function getRegister(){
        return view('business.register');
    }
    
    public function getDashboard(){
        return view('business.dashboard');
    }
}
