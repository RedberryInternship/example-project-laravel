<?php

namespace App\Library\Interactors\CronJobs;

use App\Library\Entities\CronJobs\PreChargedOrders\OrdersStopper;
use App\Library\Entities\CronJobs\PreChargedOrders\OrdersGetter;

class PreChargedOrdersChecker
{
  /**
   * Check if someone is in the initiated state
   * for to long and if so, stop him and
   * consider that he's trying to charged
   * with the battery full.
   */
  public static function check(): void
  {
    $orderIds = OrdersGetter :: get();
    OrdersStopper :: stop( $orderIds );
    
  }
}