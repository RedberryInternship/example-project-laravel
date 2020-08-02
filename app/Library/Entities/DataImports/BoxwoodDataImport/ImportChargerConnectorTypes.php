<?php

namespace App\Library\Entities\DataImports\BoxwoodDataImport;

use App\ConnectorType;
use App\Charger;

class ImportChargerConnectorTypes
{
  /**
   * Import charger connector types.
   * 
   * @return void
   */
  public static function execute(): void
  {
    $chargerConnectorsDataBridge = self :: chargerConnectorsDataBridge();
    $connectorTypeDataBridge     = self :: connectorTypesDataBridge();

    foreach( Charger :: all() as $charger )
    {
      $chargerConnector = $chargerConnectorsDataBridge[ $charger -> old_id ];
      $connectorTypeId  = $connectorTypeDataBridge[ $chargerConnector -> type ];
      
      $charger -> connector_types() -> attach( $connectorTypeId, [ 'm_connector_type_id' => $chargerConnector -> connector_id ] );
    }
  }

  /**
   * get charger data bridge.
   * 
   * @return array
   */
  public static function chargerConnectorsDataBridge(): array
  {
    $chargerConnectors = [];
    foreach( DataGetter :: get( 'charger_connectors' ) as $chargerConnector )
    {
      $chargerConnectors[ $chargerConnector -> charger_id ] = $chargerConnector;
    }

    return $chargerConnectors;
  }

  /**
   * get connector types data bridge.
   * 
   * @return array
   */
  public static function connectorTypesDataBridge(): array
  {
    $connectorTypes = [];
    foreach( ConnectorType :: all() as $connectorType )
    {
      $connectorTypes[ $connectorType -> name ] = $connectorType -> id;
    }

    return $connectorTypes;
  }
}