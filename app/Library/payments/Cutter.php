<?php

namespace App\Library\Payments;

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
      -> execute();
  }
}