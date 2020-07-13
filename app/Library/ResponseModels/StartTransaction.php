<?php

namespace App\Library\ResponseModels;

class StartTransaction
{
  const SUCCESS       = 'SUCCESS';
  const FAILED        = 'FAILED';
  const NOT_CONFIRMED = 'NOT_CONFIRMED';

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
    return $this ->transactionID;
  }
}