<?php

namespace App\Library\Entities\Payments;

use App\Library\Adapters\Payments\Refunder as GeorgianCardRefunder;
use App\Enums\PaymentType as PaymentTypeEnum;
use App\Payment as PaymentModel;
use App\Order;

class Refunder
{
  /**
   * make refund.
   * 
   * @param  Order $order
   * @param  int   $amount
   * @return void
   */
  public static function refund( Order $order, int $amount ): void
  {
    $lastPayment = $order 
      -> payments() 
      -> whereType( PaymentTypeEnum :: CUT )
      -> latest()
      -> first();
      
    $trxId  = $lastPayment -> trx_id;
    $RRN    = $lastPayment -> prrn;
    
    GeorgianCardRefunder :: refund( $trxId, $RRN, $amount );
    
    PaymentModel         :: create(
      [
        'user_card_id' => $order -> user_card_id,
        'order_id'     => $order -> id,
        'trx_id'       => null, # @ refund doesn't have trx_id
        'price'        => $amount / 100,
        'prrn'         => null, # @ refund doesn't have rrn
        'type'         => PaymentTypeEnum :: REFUND,
      ]
    );
  }
}