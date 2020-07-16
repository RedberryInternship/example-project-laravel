<?php

namespace App\Library\Entities\Payments;

use App\Library\Adapters\Payments\Finer as GeorgianCardFiner;
use App\Order;

class Finer
{
  /**
   * Make usual cutting transaction.
   * 
   * @param  Order $order
   * @param  int   $amount
   * @return void
   */
  public static function charge( Order $order, int $amount ): void
  {
    $orderId    = $order -> id;
    $userId     = $order -> user_id;
    $userCardId = $order -> user_card_id;
    $report     = $order -> charger_connector_type -> report;
    $accountId  = $order -> charger_connector_type -> terminal -> indicator;

    GeorgianCardFiner :: charge( $orderId, $userId, $userCardId, $amount, $accountId, $report );
  }
}