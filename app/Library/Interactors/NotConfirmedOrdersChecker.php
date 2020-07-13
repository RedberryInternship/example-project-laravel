<?php

namespace App\Library\Interactors;

use App\Library\Entities\CheckOrders\ChargingProcessDataGetter;
use App\Library\Entities\CheckOrders\OrderChecker;
use App\Library\Entities\CheckOrders\OrderEditor;

class NotConfirmedOrdersChecker
{
  /**
   * Check not confirmed orders.
   * 
   * @param  int $chargerTransactionId
   * @return void
   */
  public static function check( $chargerTransactionId ): void
  {
    $result     = ChargingProcessDataGetter :: get  ( $chargerTransactionId );
    $foundOrder = OrderChecker              :: check( $result               );

    OrderEditor :: updateIfExists( $foundOrder );
  }
}