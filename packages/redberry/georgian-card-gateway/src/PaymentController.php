<?php

namespace Redberry\GeorgianCardGateway;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;

class PaymentController extends Controller
{
    public function getPayment(Request $request, $param)
    {
	    Log::info($request -> all());

        if($param == 'avail-check'){
            $response  = 
            '<payment-avail-response>
                <result>
                <code>1</code>
                <desc>Successful</desc>
                </result>
                <merchant-trx>'.$request['trx_id'].'</merchant-trx>
                <purchase>
                <shortDesc>TID:3825180</shortDesc>
                <longDesc>PIN:186611</longDesc>
                <account-amount>
                <id>'.config('georgian-card-gateway.account_id').'</id>
                <amount>'.$request['o_amount'].'</amount>
                <currency>981</currency>
                <exponent>2</exponent>
                </account-amount>
                </purchase>
            </payment-avail-response>';

            return Response($response);
        }elseif($param == 'register'){
            $trx_id                     =  $request['trx_id'];
            $order_id                   =  $request['o_order_id'];
            $amount                     =  $request['o_amount'] * 100;
            $p_rrn                      =  $request['p_rrn'];
            $p_transmissionDateTime     =  $request['p_transmissionDateTime'];
            $signature                  =  $request['signature'];
            $p_authcode                 =  $request['p_authcode'];
            $result_code                =  $request['result_code'];
            LOG::info('result_code = '.$result_code);
            if($result_code == 1){
                $response = '<register-payment-response>
                    <result>
                    <code>1</code>
                    <desc>OK</desc>
                    </result>
                    </register-payment-response>';

                    // CREATE DATABASE RECORD

            }elseif($result_code == 2){
                $response = '<register-payment-response>
                    <result>
                    <code>2</code>
                    <desc>Temporary unavailable</desc>
                    </result>
                    </register-payment-response>';
            }

            return Response($response);

        }
    }

    public function getFailed()
   	{
   		
   	}
   	public function getSucceed(Request $request)
   	{
   		Log::info('success : '.$request -> all());
   	}

    public function getTest()
    {
        return view('payment::test');
    }
}
