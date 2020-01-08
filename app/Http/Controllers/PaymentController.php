<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function getPayment(Request $request, $param)
    {
    	if ($param == 'avail-check')
    	{
    		dd($param);
    	}elseif ($param == 'register') {
    		dd($param);
    	}
    }

    public function getFail()
   	{
   		dd('fail');
   	}
   	public function getSucceeded()
   	{
   		dd('successed');
   	}
}
