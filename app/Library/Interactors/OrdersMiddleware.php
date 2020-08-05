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
    Log :: channel( 'orders-check' ) -> info(
      [
        'STEP 0' => [
          'transaction_id' => $chargerTransactionId,
        ]
      ]
    );

    $result     = ChargingProcessDataGetter :: get  ( $chargerTransactionId );
    Log :: channel( 'orders-check' ) -> info(
      [
        'STEP 1 => results' => [
          'chargerID'   => $result -> getChargerId(),
          'connectorID' => $result -> getChargerConnectorTypeId(),
        ]
      ]
    );

    $foundOrder = OrderFinder               :: instance( $result ) -> find();
    Log :: channel( 'orders-check' ) -> info(
      [
        'STEP 2 => foundOrder' => [
          'foundOrder' => $foundOrder ? $foundOrder -> toArray() : null,
        ]
      ]
    );

    $foundOrder && OrderEditor :: instance()
      -> setChargerTransactionId( $chargerTransactionId )
      -> setChargerAttributes   ( $result               )
      -> setOrder               ( $foundOrder           )
      -> digest();
  }
}