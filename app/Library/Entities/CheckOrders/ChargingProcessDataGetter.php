<?php

namespace App\Library\Entities\CheckOrders;

use App\Library\DataStructures\RealChargerAttributes;
use App\Facades\Charger as RealCharger;
use Illuminate\Support\Facades\Log;

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
    Log :: channel( 'orders-check' ) -> info( 'STEP 0.2 | ' . $chargerTransactionId );
    $chargerCode     = $transactionInfo -> chargePointCode;
    Log :: channel( 'orders-check' ) -> info( 'STEP 0.3 | ' . $chargerTransactionId );
    $chargerId       = Charger :: whereCode( $chargerCode ) -> first() -> charger_id;
    Log :: channel( 'orders-check' ) -> info( 'STEP 0.4 | ' . $chargerTransactionId );
    return RealChargerAttributes :: instance()
      -> setChargerId              ( $chargerId                      )
      -> setChargerConnectorTypeId ( $transactionInfo -> connectorId );
  }
}