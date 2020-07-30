<?php

namespace App\Library\Entities\ChargingStart;

use App\Library\DataStructures\StartTransaction as StartTransactionResponse;

class ChargingProcessStarter
{
  /**
   * Start charging process via requesting 
   * real charger start service.
   * 
   * @param int $realChargerId
   * @param int $realChargerConnectorTypeId
   */
  public static function start( $realChargerId, $realChargerConnectorTypeId ): StartTransactionResponse
  {
    $startChargingResult = (new StartRequest) 
        -> setChargerId( $realChargerId )
        -> setConnectorTypeId( $realChargerConnectorTypeId )
        -> execute();

    return $startChargingResult;
  }
}