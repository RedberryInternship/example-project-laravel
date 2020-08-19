<?php

namespace App\Library\DataStructures;

use App\Facades\Charger;

class StartTransaction
{
  const SUCCESS        = 'SUCCESS';
  const FAILED         = 'FAILED';
  const NOT_CONFIRMED  = 'NOT_CONFIRMED';
  const OUT_OF_NETWORK = 'OUT_OF_NETWORK';

  /**
   * Transaction status.
   * 
   * @var string $transactionStatus
   */
  private $transactionStatus;

  /**
   * Transaction ID.
   * 
   * @var string|int $transactionID
   */
  private $transactionID;

  /**
   * Set transaction status.
   * 
   * @param  string $status
   * @return void
   */
  public function setTransactionStatus( string $status ): void
  {
    $this -> transactionStatus = $status;
  }

  /**
   * Set transaction ID.
   * 
   * @param string|int $transactionID
   */
  public function setTransactionID( $transactionID ): void
  {
    $this -> transactionID = $transactionID;
  }

  /**
   * Get transaction status.
   * 
   * @return string
   */
  public function getTransactionStatus(): string
  {
    return $this -> transactionStatus;
  }

  /**
   * Get transaction ID.
   * 
   * @return string|int
   */
  public function getTransactionID()
  {
    return $this -> transactionID;
  }

  /**
   * Determine if transaction has succeeded.
   * 
   * @return bool
   */
  public function didSucceeded(): bool
  {
    return $this -> transactionStatus == self :: SUCCESS;
  }

  /**
   * Fetch transaction info.
   * 
   * @return object
   */
  public function fetchTransactionInfo(): object
  {
    return Charger :: transactionInfo( $this -> transactionID );
  }
}