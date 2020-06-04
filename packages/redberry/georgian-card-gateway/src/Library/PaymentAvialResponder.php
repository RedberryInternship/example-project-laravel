<?php

namespace Redberry\GeorgianCardGateway\Library;

use Redberry\GeorgianCardGateway\Responses\PaymentAvail;
use Illuminate\Http\Request;

class PaymentAvailResponder
{
  private $request;
  private $handler;

  public function __construct( Request $request )
  {
    $this -> handler = resolve( 'redberry.georgian-card.handler' );
    $this -> request = $request;
  }
  
  public function respond()
  {
    $trxId       = $this -> request -> get( 'trx_id' );
    $orderAmount = $this -> request -> get( 'o_amount' );

    $paymentAvail = new PaymentAvail;
    $paymentAvail -> setResultCode( 1 );
    $paymentAvail -> setResultDesc( 'Successful' );
    $paymentAvail -> setMerchantTRX( $trxId );
    $paymentAvail -> setPurchaseShortDesc( 'order' );
    $paymentAvail -> setPurchaseLongDesc( 'order description' );
    $paymentAvail -> setPurchaseAmount( $orderAmount );
    
    $primaryTrxPcid = $this -> handler -> getPrimaryTransactionId( $this -> request );

    if( !! $primaryTrxPcid )
    {
        $paymentAvail -> setPrimaryTrxPcid( $primaryTrxPcid );
    }

    return $paymentAvail -> response();
  }
}