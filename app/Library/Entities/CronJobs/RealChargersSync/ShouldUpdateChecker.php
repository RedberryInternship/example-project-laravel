<?php

namespace App\Library\Entities\CronJobs\RealChargersSync;

class ShouldUpdateChecker
{
  /**
   * Check if charger should 
   * be updated.
   * 
   * @return bool
   */
  public static function checkCharger( $localCharger, $realCharger ): bool
  {
    return   $localCharger -> charger_id  != $realCharger[ 'charger_id' ]
          || $localCharger -> code        != $realCharger[ 'code'       ] 
        # || $localCharger -> description != $realCharger[ 'description'] 
          || $localCharger -> active      != $realCharger[ 'active'     ]
          || $localCharger -> lat         != $realCharger[ 'lat'        ] 
          || $localCharger -> lng         != $realCharger[ 'lng'        ]
          || $localCharger -> status      != $realCharger[ 'status'     ];
  }

  /**
   * Check if charger connectors
   * should be updated.
   * 
   * @return bool
   */
  public static function checkConnectors( $localCharger, $parsedRealCharger )
  {
    $connectors    = $parsedRealCharger[ 'connectors' ];

    $oldConnectors = $localCharger
      -> connector_types
      -> pluck('name', 'pivot.m_connector_type_id')
      -> all();

    array_walk( $connectors, function(&$item) { $item = (array) $item; });
    
    $connectorIds    = array_column ( $connectors  , 'id'            );
    $connectorTypes  = array_column ( $connectors  , 'type'          );
    $newConnectors   = array_combine( $connectorIds, $connectorTypes );

    ksort($oldConnectors);
    ksort($newConnectors);

    return $oldConnectors != $newConnectors;
  }
}