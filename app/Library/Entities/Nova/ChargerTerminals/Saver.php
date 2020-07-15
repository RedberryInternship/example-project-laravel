<?php

namespace App\Library\Entities\Nova\ChargerTerminals;

use App\Library\RequestModels\ChargerTerminals as ChargerTerminalsRequest;

use App\ChargerConnectorType;

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