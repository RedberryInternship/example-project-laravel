<?php

namespace Redberry\GeorgianCardGateway\Library;

use Redberry\GeorgianCardGateway\Responses\RegisterPayment;
use Redberry\GeorgianCardGateway\Contracts\GeorgianCardHandler;

class RegisterPaymentResponder
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
   * Build new instance.
   * 
   * @return self
   */
  public static function build()
  {
    return new self;
  }

  /**
   * Set Georgian Card handler.
   */
  public function __construct()
  {
    $this -> handler = resolve( 'redberry.georgian-card.handler' );
    $this -> request = request();
  }

  /**
   * Respond with register payment xml.
   * 
   * @return xml
   */
  public function respond()
  {
    $resultCode         = $this -> request -> get( 'result_code'  );
    $resultDesc         = $this -> getResultDescription(); 
    $data               = new RegisterPayment;
    
    $data -> setResultCode( $resultCode );
    $data -> setResultDesc( $resultDesc );

    $data = $this -> handler -> registerPayment( $data );

    return $data -> response();
  }

  /**
   * get Result Description.
   * 
   * @return  string
   */
  public function getResultDescription()
  {
    if( $this -> isTransactionSuccessful() )
    {
      return 'OK';
    }
   
    return 'Temporary unavailable';
  }
  
  /**
   * Determine if transaction is successful.
   * 
   * @return bool
   */
  private function isTransactionSuccessful()
  {
    return $this -> request -> get( 'result_code'  ) == 1;
  }
}