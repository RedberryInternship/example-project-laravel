<?php

namespace App\Library\Interactors;

use App\Library\Entities\CheckOrders\ChargingProcessDataGetter;
use App\Library\Entities\CheckOrders\OrderFinder;
use App\Library\Entities\CheckOrders\OrderEditor;
use Illuminate\Support\Facades\Log;

class OrdersMiddleware
{
  /**
   * Check not confirmed orders and orders that are 
   * abnormal and should stop charging.
   * 
   * @param  int $chargerTransactionId
   * @return void
   */
  public static function check( $chargerTransactionId ): void
  {    
    $result     = ChargingProcessDataGetter :: get  ( $chargerTransactionId );
    $foundOrder = OrderFinder               :: instance( $result ) -> find();
    
    OrderEditor :: instance()
      -> setChargerTransactionId( $chargerTransactionId )
      -> setChargerAttributes   ( $result               )
      -> setOrder               ( $foundOrder           )
      -> digest();
  }
}