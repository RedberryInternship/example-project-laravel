<?php

namespace Redberry\GeorgianCardGateway;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function getPayment(Request $request, $param)
    {
       if($param == 'avail-check'){
            if($result_code == 1){
                $response  = 
                '<payment-avail-response>

                    <result>

                    <code>1</code>
                    <desc>some desc</desc>
                    </result>
                    <merchant-trx>'.$request -> get('trx_id').'</merchant-trx>
                    <purchase>
                    <shortDesc>shord desc</shortDesc>
                    <longDesc>long desc</longDesc>
                    <account-amount>

                    <id>'.$request -> get('account_id').'</id>
                    <amount>'.$request -> get('o_amount').'</amount>
                    <currency>981</currency>
                    <exponent>2</exponent>

                    </account-amount>

                    </purchase>

                </payment-avail-response>';
            }elseif($result_code == 2){
                $response   = '<payment-avail-response>
                    <result>
                    <code>2</code>
                    <desc>Unable to accept this payment</desc>
                    </result>
                    </payment-avail-response>';
            }

            return Response($response);

        }elseif($param == 'register'){

            Log::info($request);

            $trx_id                     =  $request['trx_id'];
            $order_id                   =  $request['o_order_id'];
            $amount                     =  $request['o_amount'] * 100;
            $market_id                  =  $request['o_market_id'];
            $p_rrn                      =  $request['p_rrn'];
            $p_transmissionDateTime     =  $request['p_transmissionDateTime'];
            $signature                  =  $request['signature'];
            $p_authcode                 =  $request['p_authcode'];

            
            Log::info($p_rrn);

            $result_code = $request['result_code'];

            if($result_code == 1){
                $response = '<register-payment-response>
                    <result>
                    <code>1</code>
                    <desc>OK</desc>
                    </result>
                    </register-payment-response>';

                    $user_balance = UserBalances::create([
                        'market_id'         => $market_id,
                        'user_id'           => $user_id,
                        'amount'            => $amount,
                        'status'            => $result_code
                    ]);

            }elseif($result_code == 2){
                $response = '<register-payment-response>
                    <result>
                    <code>2</code>
                    <desc>Temporary unavailable</desc>
                    </result>
                    </register-payment-response>';
            }

            $user_transaction   = UserTransactions::create([
                'market_id'                 => $market_id,
                'user_id'                   => $user_id,
                'amount'                    => $amount,
                'transaction_id'            => $trx_id,
                'status'                    => $result_code,
                'p_rnn'                     => $p_rrn,
                'p_authcode'                => $p_authcode,
                'p_transmissionDateTime'    => $p_transmissionDateTime,
                'signature'                 => $signature
            ]);

            return Response($response);

        }
    }

    public function getFailed()
   	{
   		
   	}
   	public function getSucceed()
   	{
   		
   	}

    public function getTest()
    {
        return view('payment::test');
    }
}
