<?php

namespace App\Library\Interactors\CronJobs;

use App\Library\Entities\CronJobs\OnHoldSwitch\OrdersGetter;
use App\Library\Entities\CronJobs\OnHoldSwitch\OrdersOnHolder;

class OnHoldSwitcher
{
  /**
   * Switch orders to ON_HOLD if
   * app has not received feedback
   * from chargers back for x minutes.
   * 
   * @return void
   */
  public static function execute(): void
  {
    $shouldBeOnHoldedOrders = OrdersGetter :: get();
    OrdersOnHolder :: execute( $shouldBeOnHoldedOrders );
  }
}