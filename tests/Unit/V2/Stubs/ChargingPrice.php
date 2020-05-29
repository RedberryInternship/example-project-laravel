<?php

namespace Tests\Unit\V2\Stubs;

use App\ChargingPrice as ChargingPriceModel;
use App\FastChargingPrice;
class ChargingPrice
{
  public static function createChargingPricesWithOnePhaseOfDay( $chargerConnectorTypeId )
  {
    factory( ChargingPriceModel :: class ) -> create(
      [
        'charger_connector_type_id' => $chargerConnectorTypeId,
        'min_kwt'                   => 0,
        'max_kwt'                   => 5,
        'start_time'                => '00:00',
        'end_time'                  => '24:00',
        'price'                     => 50,
      ]
    );

    factory( ChargingPriceModel :: class ) -> create(
      [
        'charger_connector_type_id' => $chargerConnectorTypeId,
        'min_kwt'                   => 6,
        'max_kwt'                   => 20,
        'start_time'                => '00:00',
        'end_time'                  => '24:00',
        'price'                     => 70,
      ]
    );
    
    factory( ChargingPriceModel :: class ) -> create(
      [
        'charger_connector_type_id' => $chargerConnectorTypeId,
        'min_kwt'                   => 21,
        'max_kwt'                   => 10000000,
        'start_time'                => '00:00',
        'end_time'                  => '24:00',
        'price'                     => 95,
      ]
    );
  }

  public static function createFastChargingPrices( $chargerConnectorTypeId )
  {
    factory( FastChargingPrice :: class ) -> create(
      [
        'start_minutes'             => 1,
        'end_minutes'               => 10,
        'price'                     => 1,
        'charger_connector_type_id' => $chargerConnectorTypeId,
      ]
    );

    factory( FastChargingPrice :: class ) -> create(
      [
        'start_minutes'             => 11,
        'end_minutes'               => 20,
        'price'                     => 2,
        'charger_connector_type_id' => $chargerConnectorTypeId,
      ]
    );

    factory( FastChargingPrice :: class ) -> create(
      [
        'start_minutes'             => 21,
        'end_minutes'               => 1000000,
        'price'                     => 5,
        'charger_connector_type_id' => $chargerConnectorTypeId,
      ]
    );
  }
}