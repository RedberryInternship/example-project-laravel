<?php

namespace App\Library\Entities\ChargingStart;

use App\Library\DataStructures\StartTransaction as StartTransactionResponse;
use App\Enums\OrderStatus as OrderStatusEnum;
use App\Exceptions\Charger\StartChargingException;
use App\Order;

class OrderEditor
{
  /**
   * Order instance.
   * 
   * @var Order $order
   */
  private $order;

  /**
   * Start charging result.
   * 
   * @var StartTransactionResponse $result
   */
  private $result;

  /**
   * Return new instance.
   * 
   * @return self
   */
  public static function instance(): self
  {
    return new self;
  }

  /**
   * Is charger fast.
   * 
   * @var bool $isChargerFast
   */
  private $isChargerFast;

  /**
   * Set order.
   * 
   * @param  Order $order
   * @return self
   */
  public function setOrder( Order $order ): self
  {
    $this -> order = $order;
    return $this;
  }

  /**
   * Set start charging result.
   * 
   * @param  StartTransactionResponse $result
   * @return self
   */
  public function setStartChargingResult( StartTransactionResponse $result ): self
  {
    $this -> result = $result;
    return $this;
  }

  /**
   * Set is charger fast.
   * 
   * @param  bool $isChargerFast
   * @return self
   */
  public function setIsChargerFast( bool $isChargerFast ): self
  {
    $this -> isChargerFast = $isChargerFast;
    return $this;
  }

  /**
   * Update order according to 
   * start charging result.
   * 
   * @param  Order $order
   * @param  StartTransactionResponse $result
   * @return void
   */
  public function update(): void
  {
    $transactionID      = $this -> result -> getTransactionID();
    $transactionStatus  = $this -> result -> getTransactionStatus();
    
    $orderStatus        = self :: determineOrderStatus( $transactionStatus, $this -> isChargerFast );
    $startTimestamp     = $this -> getRealStartTimestamp();

    $this -> order -> update(
      [
        'charging_status'         => $orderStatus,
        'charger_transaction_id'  => $transactionID,
        'real_start_date'         => $startTimestamp,
      ]
    );

    if( $orderStatus == OrderStatusEnum :: OUT_OF_NETWORK )
    {
      throw new StartChargingException( OrderStatusEnum :: OUT_OF_NETWORK, 400 );
    }
  }

  /**
   * Determine order status.
   * 
   * @param  string $transactionStatus
   * @param  bool   $isChargerFast
   * @return string
   */
  private static function determineOrderStatus( string $transactionStatus, bool $isChargerFast ): string
  {
    switch( $transactionStatus )
    {
      case StartTransactionResponse :: SUCCESS:
        $orderStatus = $isChargerFast 
          ? OrderStatusEnum :: CHARGING 
          : OrderStatusEnum :: INITIATED;
      break;

      case StartTransactionResponse :: FAILED:
        $orderStatus = OrderStatusEnum :: UNPLUGGED;
      break;

      case StartTransactionResponse :: NOT_CONFIRMED:
        $orderStatus = OrderStatusEnum :: NOT_CONFIRMED;
      
      case StartTransactionResponse :: OUT_OF_NETWORK:
        $orderStatus = OrderStatusEnum :: OUT_OF_NETWORK;
    }

    return $orderStatus;
  }

  /**
   * Get start transaction timestamp.
   * 
   * @return string
   */
  private function getRealStartTimestamp()
  {
    if( $this -> result -> didSucceeded() )
    {
      return $this -> result -> fetchTransactionInfo() -> transStart / 1000;
    }
  }
}