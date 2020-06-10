<?php

namespace Redberry\GeorgianCardGateway\Responses;

class RegisterPayment extends Response
{
  public function __construct()
  {
    $this -> response = [
      'result' => [
          'code' => null,
          'desc' => null,
        ]
      ];

    $this -> wrapper  = 'register-payment-response';
  }

  public function setResultCode( int $code )
  {
    $this -> response [ 'result' ][ 'code' ] = $code;
  }
  
  public function setResultDesc( string $desc )
  {
    $this -> response [ 'result' ][ 'desc' ] = $desc;
  }
}