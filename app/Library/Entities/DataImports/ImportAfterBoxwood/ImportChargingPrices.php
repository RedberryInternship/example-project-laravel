<?php

namespace App\Library\Entities\DataImports\ImportAfterBoxwood;

use App\ChargerConnectorType;

class ImportChargingPrices
{
  /**
   * Import default charging prices.
   * 
   * @return void
   */
  public static function execute(): void
  {
    self :: importLvl2ChargingPrices();
    self :: importFastChargingPrices();
  }

  /**
   * Import LVL 2 charging prices.
   * 
   * @return void
   */
  public static function importLvl2ChargingPrices(): void
  {
    ChargerConnectorType :: all() -> each( function( $chargerConnectorType ) {
      if( ! $chargerConnectorType -> isChargerFast() )
      {
        $chargerConnectorType -> charging_prices() -> createMany(
          [
            [ 'min_kwt' => 0,  'max_kwt' => 5,   'price' => 0,  'start_time' => '00:00', 'end_time' => '24:00' ],
            [ 'min_kwt' => 6,  'max_kwt' => 10,  'price' => 0,  'start_time' => '00:00', 'end_time' => '24:00' ],
            [ 'min_kwt' => 11, 'max_kwt' => 15,  'price' => 0,  'start_time' => '00:00', 'end_time' => '24:00' ],
            [ 'min_kwt' => 16, 'max_kwt' => 22,  'price' => 0,  'start_time' => '00:00', 'end_time' => '24:00' ],
          ]
        );
      }
    });
  }

  /**
   * Import FAST charging prices.
   * 
   * @return void
   */
  public static function importFastChargingPrices()
  {
    ChargerConnectorType :: all() -> each( function( $chargerConnectorType ) {
      if( $chargerConnectorType -> isChargerFast() )
      {
        $chargerConnectorType -> fast_charging_prices() -> createMany(
          [
            ['start_minutes' => 0,  'end_minutes' => 20,   'price' => 0.5  ],
            ['start_minutes' => 21, 'end_minutes' => 40,   'price' => 0.35 ],
            ['start_minutes' => 41, 'end_minutes' => 1000, 'price' => 0.25 ],
          ]
        );
      }
    });
  }
}