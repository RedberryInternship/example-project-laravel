<?php

namespace App\Library\Entities\Business\Groups;

use App\Group;

class GroupChargersChargingPricesDestroyer
{
  /**
   * Destroy group chargers charging prices.
   * 
   * @param int $groupId
   * @return void
   */
  public static function destroy( $groupId )
  {
    $group = Group :: with(
      [
          'chargers.charger_connector_types.charging_prices',
          'chargers.charger_connector_types.fast_charging_prices',
      ]
    ) -> find($groupId);

    $group -> chargers -> each(function( $charger ) {
        $charger -> charger_connector_types -> each( function( $chargerConnectorType ) {
            $chargerConnectorType -> charging_prices -> each(function( $chargingPrice) {
                $chargingPrice -> delete();
            });
            
            $chargerConnectorType -> fast_charging_prices -> each(function( $fastChargingPrice) {
                $fastChargingPrice -> delete();
            });
        }); 
    });
  }
}