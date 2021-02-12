<?php

namespace App\Library\Interactors;

use App\Library\Entities\ChargingUpdate\UpdateFastChargerOrder;
use App\Library\Entities\ChargingUpdate\UpdateLvl2ChargerOrder;
use App\Library\Entities\ChargingProcess\CacheOrderDetails;
use App\Library\Entities\ChargingUpdate\OrderGetter;
use App\Library\Entities\Log;

class ChargingUpdater
{
  /**
   * Update charging process from real chargers back,
   * and if necessary make payment transactions.
   * 
   * @param int $transactionId
   * @param int $value
   * @return void
   */
  public static function update( $transactionId, $value ): void
  {
    $order = OrderGetter :: get( $transactionId );

    if( ! $order )
    {
      Log :: noOrderToUpdate( $transactionId );
      return;
    }

    OrdersMiddleware :: check( $transactionId );
    
    $order -> kilowatt -> updateConsumedKilowatts( $value );
    $isChargerFast = $order -> charger_connector_type -> isChargerFast();

    $isChargerFast
      ? UpdateFastChargerOrder :: execute( $order )
      : UpdateLvl2ChargerOrder :: execute( $order );

    Firebase :: sendActiveOrders( $order -> user_id );
    CacheOrderDetails :: execute( $order );
    Log :: orderSuccessfullyUpdated( $transactionId, $value );
  }
}