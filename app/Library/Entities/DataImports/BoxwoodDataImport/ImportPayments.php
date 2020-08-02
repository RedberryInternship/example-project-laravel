<?php

namespace App\Library\Entities\DataImports\BoxwoodDataImport;

use App\Enums\PaymentType as PaymentTypeEnum;
use App\UserCard;
use App\Payment;
use App\Order;

class ImportPayments
{
  /**
   * Import payments.
   * 
   * @return void
   */
  public static function execute(): void
  {
    $payments           = DataGetter :: get( 'payment' );
    $formattedPayments  = self :: format( $payments );

    Payment :: insert( $formattedPayments );
  }

  /**
   * Format payments.
   * 
   * @return array
   */
  public static function format( $payments ): array
  {
    $userCardsDataBridge = self :: userCardsDataBridge();
    $ordersDataBridge    = self :: ordersDataBridge();

    $mappedPayments = array_map( function( $payment ) use ( $userCardsDataBridge, $ordersDataBridge ) {
      return [
        'old_id'       => $payment -> id,
        'order_id'     => @ $ordersDataBridge[ $payment -> order_id ],
        'type'         => ( $payment -> price < 1 ) ? ( PaymentTypeEnum :: CHECK ) : ( PaymentTypeEnum :: CUT ),
        'confirmed'    => $payment -> confirmed,
        'confirm_date' => $payment -> confirm_date,
        'price'        => ( $payment -> price > $payment -> actual_price ) ? ( $payment -> price ) : ( $payment -> actual_price),
        'prrn'         => $payment -> prrn,
        'trx_id'       => $payment -> trx_id,
        'user_card_id' => @ $userCardsDataBridge[ $payment -> credit_card_id ],
      ];
    }, $payments );

    return array_filter( $mappedPayments, function( $payment ) {
      return !! $payment[ 'order_id' ]  && !! $payment[ 'user_card_id' ];
    });
  }

  /**
   * User cards data bridge.
   * 
   * @return array
   */
  public static function userCardsDataBridge(): array
  {
    $userCardsDataBridge = [];
    foreach( UserCard :: all() as $userCard )
    {
      $userCardsDataBridge[ $userCard -> old_id ] = $userCard -> id;
    }

    return $userCardsDataBridge;
  }

  /**
   * Orders data bridge.
   * 
   * @return array
   */
  public static function ordersDataBridge(): array
  {
    $ordersDataBridge = [];
    foreach( Order :: all() as $order )
    {
      $ordersDataBridge[ $order -> old_id ] = $order -> id;
    }

    return $ordersDataBridge;
  }
}