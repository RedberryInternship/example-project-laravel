<?php

namespace App\Library\Interactors\CronJobs;

use App\Library\Entities\CronJobs\NotConfirmedOrders\OrdersGetter;
use App\Library\Entities\CronJobs\NotConfirmedOrders\OrdersFilter;
use App\Library\Entities\CronJobs\NotConfirmedOrders\OrdersEditor;

class NotConfirmedOrdersChecker
{
  /**
   * Check not confirmed orders, which 
   * are delayed more then X minutes.
   * 
   * @return void
   */
  public static function check(): void
  {
    $notConfirmedOrders = OrdersGetter :: get();
    $filteredOrders     = OrdersFilter :: execute( $notConfirmedOrders );

    OrdersEditor :: update( $filteredOrders );
  }
}