<?php

namespace Redberry\GeorgianCardGateway\Controllers;

use Redberry\GeorgianCardGateway\Responses\RegisterPayment;
use Redberry\GeorgianCardGateway\Responses\PaymentAvail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
       /* > Request   |
        * |-----------|--------------------------------------|
        * | trx_id    |   > 758843E9FDCB2AEA868EB175D534F082 |
        * | lang_code |   > KA                               |
        * | merch_id  |   > C49D12253462A2469BBF31569F8C6B8A |
        * | o_amount  |   > 2                                |
        * | ts        |   > 20200528 14:39:23                |
        * |-----------|--------------------------------------|
        */

        Log :: channel( 'payment-responses' ) -> info(
            [
                'payment_avail_response' => request() -> all(),
            ]
        );
        $order = DB :: table( 'orders' ) -> where( 'id', 77777 ) -> first();


        $trxId       = request() -> get( 'trx_id' );
        $orderAmount = request() -> get( 'o_amount' );

        $paymentAvail = new PaymentAvail;
        $paymentAvail -> setResultCode( 1 );
        $paymentAvail -> setResultDesc( 'Successful' );
        $paymentAvail -> setMerchantTRX( $trxId );
        $paymentAvail -> setPurchaseShortDesc( 'order' );
        $paymentAvail -> setPurchaseLongDesc( 'order description' );
        $paymentAvail -> setPurchaseAmount( $orderAmount );
        
        if( $order )
        {
            $paymentAvail -> setPrimaryTrxPcid( $order -> comment );
           #$paymentAvail -> setTransactionTypeToCardRegister();
           #$paymentAvail -> setCardPresentMode( true );
        }

        return $paymentAvail -> response();
    }

    public function registerPaymentResponse()
    {

       /* > Request
        * |------------------------|-------------------------------------|
        * | trx_id                 |  > 758843E9FDCB2AEA868EB175D534F082 |
        * | merch_id               |  > C49D12253462A2469BBF31569F8C6B8A |
        * | merchant_trx           |  > 758843E9FDCB2AEA868EB175D534F082 |
        * | result_code            |  > 1                                |
        * | amount                 |  > 2                                |
        * | account_id             |  > 6ED073BE2DCFDC1FF992115BAD7771EF |
        * | o_amount               |  > 2                                |
        * | p_expiryDate           |  > 2010                             | 
        * | p_isFullyAuthenticated |  > Y                                |
        * | p_maskedPan            |  > 900000xxxxxxxxx0001              | 
        * | p_cardholder           |  > Test                             | 
        * | ts                     |  > 20200528 14:39:36                | 
        * | signature              |  > DIV6mFAcjJv4CBM9Mk2...longString | 
        * |------------------------|-------------------------------------|
        */

        Log :: channel( 'payment-responses' ) -> info(
            [
                'register_payment_response' => request() -> all(),
            ]
        );

        $order = DB :: table( 'orders' ) -> where( 'id', 77777 ) -> first();

        if(! $order )
        {
            factory( \App\Order :: class ) -> create(
                [
                    'comment' => request() -> get( 'trx_id' ),
                ]
            );
        }

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
