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

    $data = new PaymentAvail;
    $data -> setResultCode( 1 );
    $data -> setResultDesc( 'Successful' );
    $data -> setMerchantTRX( $trxId );
    $data -> setPurchaseShortDesc( 'order' );
    $data -> setPurchaseAmount( $orderAmount );
    
    $paymentAvail = $this -> handler -> paymentAvail( $data );

    return $paymentAvail -> response();
  }
}