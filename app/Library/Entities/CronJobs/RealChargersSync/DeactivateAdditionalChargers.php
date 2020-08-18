<?php

namespace App\Library\Entities\CronJobs\RealChargersSync;

use App\Charger;

class DeactivateAdditionalChargers
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

    \Log :: info( $shouldBeDeactivated );
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
    return Charger :: pluck('id') -> toArray();   
  }
}