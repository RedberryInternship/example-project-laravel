<?php

namespace App\Library\Entities\CheckOrders;

use App\Library\ResponseModels\RealChargerAttributes;

use App\Facades\Charger;

class ChargingProcessDataGetter
{
  /**
   * Get data about charger that's being charged.
   * 
   * @param int $chargerTransactionId
   */
  public static function get( $chargerTransactionId ): RealChargerAttributes
  {
    $chargerInfo = Charger :: transactionInfo( $chargerTransactionId );

    return RealChargerAttributes :: instance()
      -> setChargerId              ( $chargerInfo -> id          )
      -> setChargerConnectorTypeId ( $chargerInfo -> connectorId );
  }
}