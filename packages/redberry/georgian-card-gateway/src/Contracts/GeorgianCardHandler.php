<?php

namespace Redberry\GeorgianCardGateway\Contracts;

interface GeorgianCardHandler
{
  /**
   * Get primary transaction id
   * for recurrent transactions.
   * 
   * @param   int $userCardId
   * @return  string|null
   */
  function getPrimaryTransactionId( $userCardId );


  /**
   * Save card with user id and
   * user card information.
   * 
   * @param   int     $primaryTrixId
   * @param   int     $userId
   * @param   object  $userCardInfo
   * 
   * @return  void
   */
  function saveCard( $primaryTrixId, $userId, $userCardInfo );

  /**
   * Update user card RRN for
   * refund operations.
   * In order to refund you always need 
   * last transaction/operation identifier 
   * which is RRN.
   * 
   * @param   int     $userCardId
   * @param   string  $RRN
   */
  function updateCardRRN( $userCardId, $RRN );

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
  function failed();
}