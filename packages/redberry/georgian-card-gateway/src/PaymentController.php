<?php

namespace Redberry\GeorgianCardGateway;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Giunashvili\XMLParser\Parser;

class PaymentController extends Controller
{
    private $parser;
    private $paymentAvailResponseWrapper;
    private $registerPaymentResponseWrapper;

    public function __construct( Parser $parser )
    {
        if( app() -> bound ( 'debugbar' ) )
        {
            resolve( 'debugbar' ) -> disable();
        }

        $this -> parser = $parser;
        $this -> paymentAvailResponseWrapper    = 'payment-avail-response';
        $this -> registerPaymentResponseWrapper = 'register-payment-response';
    }


    public function paymentAvailResponse()
    {
        $trxId       = request() -> get( 'trx_id' );
        $orderAmount = request() -> get( 'o_amount' );

        $response    = $this -> paymentAvailResponseStructure();

        $response [ 'merchant-trx' ] = $trxId;
        $response [ 'purchase' ][ 'account-amount' ][ 'amount' ] = $orderAmount;

        return $this -> parser -> arrayToXml( $response, $this -> paymentAvailResponseWrapper );
    }

    public function registerPaymentResponse()
    {
        $trx_id                     =  request() -> get( 'trx_id'       );
        $order_id                   =  request() -> get( 'o_order_id'   );
        $amount                     =  request() -> get( 'o_amount'     ) * 100;
        $p_rrn                      =  request() -> get( 'p_rrn'        );
        $p_transmissionDateTime     =  request() -> get( 'p_transmissionDateTime' );
        $signature                  =  request() -> get( 'signature'    );
        $p_authcode                 =  request() -> get( 'p_authcode'   );
        $result_code                =  request() -> get( 'result_code'  );
        $p_storage_card_ref         =  request() -> get( 'p.storage.card.ref' );

        $response = $this -> registerPaymentResponseStructure();
        
        if( $result_code == 1 )
        {
            $response [ 'result' ][ 'code' ] = 1;
            $response [ 'result' ][ 'desc' ] = 'OK';
        }
        else 
            if( $result_code == 2 )
            {
                $response [ 'result' ][ 'code' ] = 2;
                $response [ 'result' ][ 'desc' ] = 'Temporary unavailable';
            }

        return response( $this -> parser -> arrayToXml( $response, $this -> registerPaymentResponseWrapper ) );
    }

    public function getFailed()
   	{
        $params = request() -> all();
   		Log::info([ 'payment_failed_params' => $params ]);
    }
       
   	public function getSucceed()
   	{
        $params = request() -> all();
   		Log::info([ 'payment_succeed_params' => $params ]);
   	}

    public function initiate()
    {
        return view('payment::test');
    }

    private function paymentAvailResponseStructure()
    {
        return [
            'result' => [
                'code' => 1,
                'desc' => 'Successful',
            ],
            'merchant-trx' => '',
            'purchase' => [
                'shortDesc' => 'TID:3825180',
                'longDesc' => 'PIN:186611',
                'account-amount' => [
                    'id' => config('georgian-card-gateway.account_id'),
                    'amount' => 0,
                    'currency' => 981,
                    'exponent' => 2,    
                ],
            ],
        ];
    }

    private function registerPaymentResponseStructure()
    {
        return [
            'result' => [
                'code' => 0,
                'desc' => '',
            ]
        ];
    }
}
