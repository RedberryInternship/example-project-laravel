<?php

namespace Redberry\GeorgianCardGateway;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

    public function getFailed()
   	{
   		dd('failed');
   	}
   	public function getSucceed()
   	{
   		dd('Succeed');
   	}
}
