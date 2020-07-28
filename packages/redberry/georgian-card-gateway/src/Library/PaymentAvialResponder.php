<?php

namespace Redberry\GeorgianCardGateway\Library;

use Redberry\GeorgianCardGateway\Responses\PaymentAvail;

class PaymentAvailResponder
{
  /**
   * Request.
   * 
   * @var \Illuminate\Http\Request $request
   */
  private $request;

  /**
   * Georgian card handler.
   * 
   * @var GeorgianCardHandler $handler
   */
  private $handler;

  /**
   * build new instance.
   * 
   * @return self
   */
  public static function build()
  {
    return new self;
  }

  /**
   * Construct georgian card handler.
   */
  public function __construct()
  {
    $this -> handler = resolve( 'redberry.georgian-card.handler' );
    $this -> request = request();
  }
  
  /**
   * Build response object and respond.
   * 
   * @return XML
   */
  public function respond()
  {
    $trxId         = $this -> request -> get( 'trx_id'       );
    $orderAmount   = $this -> request -> get( 'o_amount'     );
    $accountId     = $this -> request -> get( 'o_account_id' );
    $chargerReport = $this -> request -> get( 'o_charger_report' ) ?? 'No report';

    $paymentAvail = new PaymentAvail;
    $paymentAvail -> setResultCode( 1 );
    $paymentAvail -> setResultDesc( 'Successful' );
    $paymentAvail -> setMerchantTRX( $trxId );
    $paymentAvail -> setPurchaseShortDesc( 'order' );
    $paymentAvail -> setPurchaseLongDesc( $chargerReport );
    $paymentAvail -> setPurchaseAmount( $orderAmount );
    $accountId && $paymentAvail -> setAccountId( $accountId );
    
    $primaryTrxPcid = $this -> handler -> getPrimaryTransactionId( $this -> request );

    if( !! $primaryTrxPcid )
    {
        $paymentAvail -> setPrimaryTrxPcid( $primaryTrxPcid );
    }

    return $paymentAvail -> response();
  }
}