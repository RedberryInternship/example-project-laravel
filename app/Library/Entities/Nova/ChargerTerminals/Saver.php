<?php

namespace App\Library\Entities\Nova\ChargerTerminals;

use App\Library\DataStructures\ChargerTerminals as ChargerTerminalsRequest;

use App\ChargerConnectorType;

/**
 * We need to indicate each charger which POS Terminal
 * it should use and preferably some additional(report)
 * information for the espace to identify from which charger 
 * transaction happened.
 * 
 * report information appears in Bank Records.
 */
class Saver
{
  /**
   * Save terminal id and report on charger.
   *
   * @param  ChargerTerminalsRequest $request
   * @return JSON
   */
  public static function save( ChargerTerminalsRequest $request )
  {
    $terminalId = $request -> getTerminalId();
    $chargerId  = $request -> getChargerId();
    $report     = $request -> getReport();

    $dataToUpdate = [];
    $report     && $dataToUpdate[ 'report'       ] = $report;
    $terminalId && $dataToUpdate[ 'terminal_id'  ] = $terminalId;

    ChargerConnectorType :: where('charger_id', $chargerId ) -> update( $dataToUpdate );

    return response() -> json([ 'success' => true ]);
  }
}
