<?php

namespace Redberry\GeorgianCardGateway\Library;

use Redberry\GeorgianCardGateway\Responses\PaymentAvail;

class PaymentAvailResponder
{
  private $handler;

  public function __construct()
  {
    $this -> handler = resolve( 'redberry.georgian-card.handler' );
  }
  
  public function respond()
  {
    $trxId       = request() -> get( 'trx_id' );
    $orderAmount = request() -> get( 'o_amount' );
    $userCardId  = request() -> get( 'o_user_card_id' );

    $paymentAvail = new PaymentAvail;
    $paymentAvail -> setResultCode( 1 );
    $paymentAvail -> setResultDesc( 'Successful' );
    $paymentAvail -> setMerchantTRX( $trxId );
    $paymentAvail -> setPurchaseShortDesc( 'order' );
    $paymentAvail -> setPurchaseLongDesc( 'order description' );
    $paymentAvail -> setPurchaseAmount( $orderAmount );
    
    $primaryTrxPcid = $this -> handler -> getPrimaryTransactionId( $userCardId );

    if( !! $primaryTrxPcid )
    {
        $paymentAvail -> setPrimaryTrxPcid( $primaryTrxPcid );
    }

    return $paymentAvail -> response();
  }
}