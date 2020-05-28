<?php

namespace Tests\Unit\V2\Stubs;

use App\ChargerConnectorType as ChargerConnectorTypeModel;
use App\Enums\ConnectorType as ConnectorTypeEnum;
use App\Enums\ChargerType as ChargerTypeEnum;
use App\ConnectorType;
use App\Charger;

class ChargerConnectorType
{
  public static function createChargerConnectorType( $chargerType = ChargerTypeEnum :: LVL2 )
  {
    $connectorTypeName    = $chargerType == ChargerTypeEnum :: LVL2 
      ? ConnectorTypeEnum :: TYPE_2   
      : ConnectorTypeEnum :: CHADEMO;   

    $connectorType        = ConnectorType :: whereName( $connectorTypeName ) -> first();
    $chargerConnectorType = factory( ChargerConnectorTypeModel :: class ) -> create(
      [
        'connector_type_id'   => $connectorType -> id,
        'charger_id'          => factory( Charger :: class ) -> create([ 'charger_id' => 29 ]) -> id,
      ]
    );

    return $chargerConnectorType;
  }
}