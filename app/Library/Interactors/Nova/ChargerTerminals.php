<?php

namespace App\Library\Interactors\Nova;

use App\Library\Entities\Nova\ChargerTerminals\ChargersGetter;
use App\Library\Entities\Nova\ChargerTerminals\TerminalsGetter;
use App\Library\Entities\Nova\ChargerTerminals\Saver;

use App\Library\RequestModels\ChargerTerminals as ChargerTerminalRequest;

class ChargerTerminals
{
  /**
   * Get all chargers with connector types.
   * 
   * @return JSON
   */
  public static function getChargers()
  {
    return ChargersGetter :: get();
  }

  /**
   * Get all post terminals.
   * 
   * @return JSON
   */
  public static function getTerminals()
  {
    return TerminalsGetter :: get();
  }

  /**
   * Save new terminal and report on charger.
   * 
   * @param  ChargerTerminalRequest $request
   * @return JSON
   */
  public static function save( ChargerTerminalRequest $request )
  {
    return Saver :: save( $request );
  }
}