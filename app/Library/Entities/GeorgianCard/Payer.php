<?php

namespace App\Library\Entities\GeorgianCard;

use App\Payment;

class Payer
{
  /**
   * Make payment.
   * 
   * @return void
   */
  public static function createPaymentRecord()
  {
    $userCardId = request() -> get( 'o_user_card_id'     );
    $orderId    = request() -> get( 'o_id'               );
    $trxId      = request() -> get( 'trx_id'             );
    $price      = request() -> get( 'o_amount'           );
    $RRN        = request() -> get( 'p_rrn'              );
    $type       = request() -> get( 'o_transaction_type' );

    Payment :: create(
      [
        'user_card_id' => $userCardId,
        'order_id'     => $orderId,
        'trx_id'       => $trxId,
        'price'        => $price / 100,
        'prrn'         => $RRN,
        'type'         => $type,
      ]
    );
  }
}