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
        
        Log :: channel( 'payment-responses' ) -> info(
            request() -> all()
        );
    }
    public function paymentAvailResponse()
    {
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
