<?php

namespace Redberry\GeorgianCardGateway\Contracts;

use Illuminate\Http\Request;

interface GeorgianCardHandler
{
  /**
   * Get primary transaction id
   * for recurrent transactions.
   * 
   * @param   Request $userCardId
   * @return  string|null
   */
  function getPrimaryTransactionId( Request $request );

  /**
   * Make necessary operation.
   * for example add user card, 
   * payments record, etc.
   * 
   * @param   Request  $primaryTrixId
   * 
   * @return  void
   */
  function update( Request $request );

  /**
   * Success method will be executed if
   * transaction is to end successfully.
   * 
   * @return mixed
   */
  function success();

  /**
   * Failed method will be executed if
   * transaction is to end with failure.
   * 
   * @return mixed
   */
  function failure();
}