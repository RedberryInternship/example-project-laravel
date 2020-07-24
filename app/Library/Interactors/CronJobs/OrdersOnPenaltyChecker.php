<?php

namespace App\Library\Interactors\CronJobs;

use App\Library\Entities\CronJobs\OrdersOnPenalty\OrdersGetter;
use App\Library\Entities\CronJobs\OrdersOnPenalty\OrdersEditor;

class OrdersOnPenaltyChecker
{
  /**
   * Check orders if they are on penalty,
   * if so then change the charging_status.
   * 
   * @return void
   */
  public static function check(): void
  {
    $orders = OrdersGetter :: get();
    OrdersEditor :: update( $orders );
  }
}