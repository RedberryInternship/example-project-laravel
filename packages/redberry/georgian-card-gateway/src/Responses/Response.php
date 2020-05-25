<?php

namespace Redberry\GeorgianCardGateway\Responses;

use Giunashvili\XMLParser\Parse;

class Response
{
  protected $response;
  protected $wrapper;


  public function response()
  {
    $convertedIntoXml = Parse :: arrayAsXml( 
      $this -> response, 
      $this -> wrapper,
    );

    return $convertedIntoXml;
  }
}