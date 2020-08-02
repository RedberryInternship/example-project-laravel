<?php

namespace App\Library\Entities\DataImports\ImportBeforeBoxwood;

use Illuminate\Support\Facades\DB;
use App\Enums\ConnectorType as ConnectorTypeEnum;

class ImportConnectorTypes
{
  /**
   * Insert connector types.
   * 
   * @return void
   */
  public static function execute(): void
  {
    DB :: table('connector_types') -> insert(
      [
        [
            'name'  => ConnectorTypeEnum :: TYPE_2,
        ],
        [
            'name'  => ConnectorTypeEnum :: COMBO_2,
        ],
        [
            'name'  => ConnectorTypeEnum :: CHADEMO,
        ]
      ]
    ); 
  }
}