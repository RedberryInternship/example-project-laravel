<?php

namespace App\Library\Payments;

use Illuminate\Support\Facades\Log;

class Payment
{
  /**
   * Make payment transaction.
   * 
   * @param   \App\Order $order
   * @param   float     $amount
   * @param   string    $payment_type
   * 
   * @return  void
   */
  public static function pay( $order, $amount, $payment_type )
  {
    $order -> load('user');

    Log :: channel( 'pay' ) -> info(
      [
        'user'          => [
          'id'    => $order -> user -> id,
          'name'  => $order -> user -> first_name . $order -> user -> last_name,
        ],
        'user_card_id'  => $order -> user_card_id,
        'order_id'      => $order -> id,
        'amount'        => $amount,
        'payment_type'  => $payment_type,
      ]
    );
  }

}