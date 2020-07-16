<?php

namespace App\Library\Adapters\Payments;

use App\Enums\PaymentType as PaymentTypeEnum;
use Redberry\GeorgianCardGateway\Transaction;

class Finer
{
  public static function charge( int $orderId, int $userId, int $userCardId, int $amount, $accountId, $report ): void
  {
    $report = urlencode( $report );
    
    (new Transaction)    
      -> setOrderId   ( $orderId    )
      -> setUserId    ( $userId     )
      -> setUserCardId( $userCardId )
      -> setAmount    ( $amount     )
      -> set( 'transaction_type', PaymentTypeEnum :: FINE )
      -> set( 'charger_report'  , $report                 )
      -> set( 'account_id'      , $accountId              )
      -> setAccountId( $accountId )
      -> execute();
  }
}