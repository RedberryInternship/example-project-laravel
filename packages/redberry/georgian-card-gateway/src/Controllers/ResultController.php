<?php

namespace Redberry\GeorgianCardGateway\Controllers;

use App\Http\Controllers\Controller;

class ResultController extends Controller
{
  private $handler;

  public function __construct()
  {
    $this -> handler = resolve( 'redberry.georgian-card.handler' );  
  }

  /**
   * Transaction ends with success.
   * 
   * @return mixed
   */
  public function succeed()
  {
    return $this -> handler -> succeed();
  }

  /**
   * Transaction ends with failure.
   * 
   * @return mixed
   */
  public function failed()
  {
    return $this -> handler -> failed();
  }
}
