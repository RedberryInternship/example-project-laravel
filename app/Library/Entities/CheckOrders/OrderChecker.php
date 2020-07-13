<?php

namespace App\Library\Entities\CheckOrders;

use App\Library\ResponseModels\RealChargerAttributes;
use App\Enums\OrderStatus as OrderStatusEnum;

use App\Order;

class OrderChecker
{
  /**
   * Check orders and if it is unconfirmed.
   * 
   * @param  int $chargerTransactionId
   * @return Order
   */
  public static function check( RealChargerAttributes $chargerInfo )
  {
    $chargerId                  = $chargerInfo -> getChargerId();
    $realChargerConnectorTypeId = $chargerInfo -> getChargerConnectorTypeId();

    return Order :: with( 'charger_connector_type.charger' )
      -> where( 'charging_status', OrderStatusEnum :: NOT_CONFIRMED )
      -> whereHas( 'charger_connector_type', function( $query ) use( $realChargerConnectorTypeId, $chargerId ) {
        $query -> where( 'm_connector_type_id', $realChargerConnectorTypeId );
        $query -> whereHas( 'charger', function( $query ) use ( $chargerId ) {
          $query -> where( 'charger_id', $chargerId );
        });
      })
      -> first();
  }
}