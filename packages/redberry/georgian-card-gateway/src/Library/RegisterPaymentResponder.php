<?php

namespace Redberry\GeorgianCardGateway\Library;

use Redberry\GeorgianCardGateway\Responses\RegisterPayment;

class RegisterPaymentResponder
{
  private $handler;

  /**
   * Set Georgian Card handler.
   */
  public function __construct()
  {
    $this -> handler = resolve( 'redberry.georgian-card.handler' );
  }

  /**
   * Respond with register payment xml.
   * 
   * @return xml
   */
  public function respond()
  {
    $resultCode         = request() -> get( 'result_code'  );
    $registerPayment    = new RegisterPayment;
    $resultDesc         = $this -> getResultDescription(); 
    
    $registerPayment -> setResultCode( $resultCode );
    $registerPayment -> setResultDesc( $resultDesc );

    if( $this -> isTransactionSuccessful() )
    {
      $this -> handler -> update();
    }

    return $registerPayment -> response();
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
    return request() -> get( 'result_code'  ) == 1;
  }
 
}