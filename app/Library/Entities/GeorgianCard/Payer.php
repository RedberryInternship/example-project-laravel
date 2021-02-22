<?php

namespace App\Library\Entities\GeorgianCard;

use App\Library\Entities\Helper;
use App\Payment;
use App\Order;

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

    if( ! Helper :: isDev() )
    {
      $price /= 100;
    }

    $order = Order :: find( $orderId );

    Payment :: create(
      [
        'user_card_id' => $userCardId,
        'order_id'     => $orderId,
        'trx_id'       => $trxId,
        'price'        => $price,
        'prrn'         => $RRN,
        'type'         => $type,
        'user_id'      => $order -> user_id,
        'company_id'   => $order -> company_id,
      ]
    );
  }
}
