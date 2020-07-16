<?php

namespace App\Library\Entities\Nova\ChargerTerminals;

use Illuminate\Support\Facades\DB;

class ChargersGetter
{
  /**
   * Get chargers with connector types.
   * 
   * @return JSON
   */
  public static function get()
  {
    $sqlQuery = 'select '
      . 'chargers.id, chargers.name, chargers.code, chargers.description, chargers.location, '
      . 'charger_connector_types.report as terminal_report, '
      . 'terminals.id as terminal_id, terminals.title as terminal_title '
      . 'from chargers '
      . 'left join charger_connector_types on chargers.id = charger_connector_types.charger_id '
      . 'left join connector_types on connector_types.id = charger_connector_types.connector_type_id '
      . 'left join terminals on charger_connector_types.terminal_id = terminals.id';
    
    $chargers = DB :: select( $sqlQuery );

    return response() -> json( $chargers );
  }
}