<?php

namespace App\Library\Entities\Payments;

use App\Library\Adapters\Payments\Cutter as GeorgianCardCutter;
use App\Order;

class Cutter
{
  /**
   * Make usual cutting transaction.
   * 
   * @param  Order $order
   * @param  int   $amount
   * @return void
   */
  public static function cut( Order $order, int $amount ): void
  {
    $orderId    = $order -> id;
    $userId     = $order -> user_id;
    $userCardId = $order -> user_card_id;
    $report     = $order -> charger_connector_type -> report;
    $accountId  = $order -> charger_connector_type -> terminal -> indicator;

    GeorgianCardCutter :: cut( $orderId, $userId, $userCardId, $amount, $accountId, $report );
  }
}