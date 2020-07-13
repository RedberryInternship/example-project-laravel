<?php

namespace App\Library\Adapters\Payments;

use App\Enums\PaymentType as PaymentTypeEnum;
use Redberry\GeorgianCardGateway\Transaction;

class Cutter
{
  public static function cut( int $orderId, int $userId, int $userCardId, int $amount ): void
  {
    (new Transaction)    
      -> setOrderId   ( $orderId    )
      -> setUserId    ( $userId     )
      -> setUserCardId( $userCardId )
      -> setAmount    ( $amount     )
      -> set( 'transaction_type', PaymentTypeEnum :: CUT )
      -> execute();
  }
}