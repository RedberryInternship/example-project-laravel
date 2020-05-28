<?php

namespace Redberry\GeorgianCardGateway\Controllers;

use Redberry\GeorgianCardGateway\Responses\RegisterPayment;
use Redberry\GeorgianCardGateway\Responses\PaymentAvail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class ResponseController extends Controller
{
    public function __construct()
    {
        if( app() -> bound( 'debugbar' ) )
        {
            resolve( 'debugbar' ) -> disable();
        }
    }
    public function paymentAvailResponse()
    {
        Log :: channel( 'payment-responses' ) -> info(
            [
                'payment_avail_response' => request() -> all(),
            ]
        );

        $trxId       = request() -> get( 'trx_id' );
        $orderAmount = request() -> get( 'o_amount' );

        $paymentAvail = new PaymentAvail;
        $paymentAvail -> setResultCode( 1 );
        $paymentAvail -> setResultDesc( 'Successful' );
        $paymentAvail -> setMerchantTRX( $trxId );
        $paymentAvail -> setPurchaseShortDesc( 'order' );
        $paymentAvail -> setPurchaseLongDesc( 'order description' );
        $paymentAvail -> setPurchaseAmount( $orderAmount );

        return $paymentAvail -> response();
    }

    public function registerPaymentResponse()
    {
        Log :: channel( 'payment-responses' ) -> info(
            [
                'register_payment_response' => request() -> all(),
            ]
        );

        $result_code        = request() -> get( 'result_code'  );
        $registerPayment    = new RegisterPayment;
        
        if( $result_code == 1 )
        {
            $registerPayment -> setResultCode( 1 );
            $registerPayment -> setResultDesc( 'OK' );
        }
        else 
            if( $result_code == 2 )
            {
                $registerPayment -> setResultCode( 2 );
                $registerPayment -> setResultDesc( 'Temporary unavailable' );
            }

        return $registerPayment -> response();
    }
}
