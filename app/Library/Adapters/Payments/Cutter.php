<?php

namespace App\Library\Adapters\Payments;

use App\Enums\PaymentType as PaymentTypeEnum;
use Redberry\GeorgianCardGateway\Transaction;

class Cutter
{
  public static function cut( int $orderId, int $userId, int $userCardId, int $amount, $accountId, $report ): void
  {
    $report = urlencode( $report );

    Transaction :: build()
      -> setOrderId   ( $orderId    )
      -> setUserId    ( $userId     )
      -> setUserCardId( $userCardId )
      -> setAmount    ( $amount     )
      -> set( 'transaction_type', PaymentTypeEnum :: CUT )
      -> set( 'charger_report'  , $report                )
      -> set( 'account_id'      , $accountId             )
      -> execute();
  }
}