<?php

namespace Redberry\GeorgianCardGateway\Controllers;

use Redberry\GeorgianCardGateway\Responses\RegisterPayment;
use Redberry\GeorgianCardGateway\Responses\PaymentAvail;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function paymentAvailResponse()
    {
        $trxId       = request() -> get( 'trx_id' );
        $orderAmount = request() -> get( 'o_amount' );

        $paymentAvail = new PaymentAvail;
        $paymentAvail -> setMerchantTRX( $trxId );
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
