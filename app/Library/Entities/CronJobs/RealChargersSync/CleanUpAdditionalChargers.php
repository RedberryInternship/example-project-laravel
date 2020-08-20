<?php

namespace App\Library\Entities\CronJobs\RealChargersSync;

use App\Enums\ChargerStatus as ChargerStatusEnum;
use App\Charger;

class CleanUpAdditionalChargers
{
  /**
   * Deactivate chargers that are present in local DB
   * but arn't coming from Real Chargers Back.
   *
   * @param  $realChargers 
   * @return void
   */
  public static function execute( $realChargers ): void
  {
    $realChargersIds  = self :: getRealChargerIds( $realChargers );
    $localChargersIds = self :: getLocalChargerIds();

    $shouldBeDeactivated = array_diff( $localChargersIds, $realChargersIds );

    if( ! empty( $shouldBeDeactivated ) )
    {
      Charger :: whereIn( 'charger_id', $shouldBeDeactivated )
        -> where( 'status', '!=', ChargerStatusEnum :: NOT_PRESENT )
        -> update(
          [ 
            'status'  => ChargerStatusEnum :: NOT_PRESENT,
          ]
        );
    }
  }

  /**
   * Get Ids from real chargers.
   * 
   * @param  array
   * @return array
   */
  private static function getRealChargerIds( $realChargers ): array
  {
    return array_map( function( $charger ) {
      return $charger -> id;
    }, $realChargers );
  }

  /**
   * Get charger Ids from local DB.
   * 
   * @return array
   */
  public static function getLocalChargerIds(): array
  {
    return Charger :: pluck('charger_id') -> toArray();   
  }
}