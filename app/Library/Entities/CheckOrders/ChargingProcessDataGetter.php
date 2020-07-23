<?php

namespace App\Library\Entities\CheckOrders;

use App\Library\ResponseModels\RealChargerAttributes;

use App\Facades\Charger as RealCharger;

use App\Charger;

class ChargingProcessDataGetter
{
  /**
   * Get data about charger that's being charged.
   * 
   * @param int $chargerTransactionId
   */
  public static function get( $chargerTransactionId ): RealChargerAttributes
  {
    $transactionInfo = RealCharger :: transactionInfo( $chargerTransactionId );
    $chargerCode     = $transactionInfo -> chargePointCode;
    $chargerId       = Charger :: whereCode( $chargerCode ) -> first() -> charger_id;

    return RealChargerAttributes :: instance()
      -> setChargerId              ( $chargerId                      )
      -> setChargerConnectorTypeId ( $transactionInfo -> connectorId );
  }
}