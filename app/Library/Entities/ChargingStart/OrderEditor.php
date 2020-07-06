<?php

namespace App\Library\Entities\ChargingStart;

use App\Library\ResponseModels\StartTransaction as StartTransactionResponse;
use App\Enums\OrderStatus as OrderStatusEnum;
use App\Enums\ChargerType as ChargerTypeEnum;

use App\ChargerConnectorType;
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
    ChargerConnectorType      $chargerConnectorType 
  ): void
  {
    $transactionID      = $result -> getTransactionID();
    $transactionStatus  = $result -> getTransactionStatus();
    
    $orderStatus        = self :: determineOrderStatus( 
      $transactionStatus,
      $chargerConnectorType,
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
   * @return string
   */
  private static function determineOrderStatus( string $transactionStatus, ChargerConnectorType $chargerConnectorType ): string
  {
    switch( $transactionStatus )
    {
      case StartTransactionResponse :: SUCCESS:
        $orderStatus = self :: determineOrderStatusWhenSuccess( $chargerConnectorType );
      break;

      case StartTransactionResponse :: FAILED:
        $orderStatus = OrderStatusEnum :: CANCELED;
      break;

      case StartTransactionResponse :: NOT_CONFIRMED:
        $orderStatus = OrderStatusEnum :: NOT_CONFIRMED;
    }

    return $orderStatus;
  }

  /**
   * Determine if charger is fast.
   * 
   * @return bool
   */
  private static function isChargerFast( ChargerConnectorType $chargerConnectorType ): bool
  {
      $chargerConnectorType = $chargerConnectorType;
      $chargerType          = $chargerConnectorType -> determineChargerType();

      return ChargerTypeEnum :: FAST == $chargerType;
  }

  /**
   * Determine order status when
   * transaction is successfully started.
   * 
   * @param  ChargerConnectorType $chargerConnectorType
   * @return string
   */
  private static function determineOrderStatusWhenSuccess( ChargerConnectorType $chargerConnectorType )
  {
    if( self :: isChargerFast( $chargerConnectorType ))
    {
      return OrderStatusEnum :: CHARGING;
    }
    
    return OrderStatusEnum :: INITIATED;
  }
}