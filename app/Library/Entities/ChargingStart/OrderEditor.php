<?php

namespace App\Library\Entities\ChargingStart;

use App\Library\ResponseModels\StartTransaction as StartTransactionResponse;
use App\Enums\OrderStatus as OrderStatusEnum;

use App\Order;

class OrderEditor
{
  /**
   * Update order according to 
   * start charging result.
   * 
   * @param  Order $order
   * @param  StartTransactionResponse $result
   * @return void
   */
  public static function update( 
    Order                     $order, 
    StartTransactionResponse  $result, 
    bool                      $isChargerFast
  ): void
  {
    $transactionID      = $result -> getTransactionID();
    $transactionStatus  = $result -> getTransactionStatus();
    
    $orderStatus        = self :: determineOrderStatus( 
      $transactionStatus,
      $isChargerFast,
    );

    $order -> update(
      [
        'charging_status'         => $orderStatus,
        'charger_transaction_id'  => $transactionID,
      ]
    );
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
        $orderStatus = OrderStatusEnum :: CANCELED;
      break;

      case StartTransactionResponse :: NOT_CONFIRMED:
        $orderStatus = OrderStatusEnum :: NOT_CONFIRMED;
    }

    return $orderStatus;
  }
}