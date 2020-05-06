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
        return redirect('/business/charger-groups');
    }
}
