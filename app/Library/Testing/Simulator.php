<?php

namespace App\Library\Testing;

use App\Facades\Charger;

class Simulator
{
  /**
   * Tell charger that it is Lvl 2 charger.
   */
  public function activateSimulatorMode( $charger_id )
  {
    //
  }

  /**
   * Give charger voltage.
   */
  public function upAndRunning( $charger_id )
  {

    $chargers = Charger :: all();

    foreach( $chargers as &$charger )
    {
      if( $charger -> id == $charger_id )
      {
        $charger -> status = 0;
      }
    }

    Charger :: setChargers( $chargers );
  }

  /**
   * Plug connector cable of the charger.
   * 
   * @param int $charger_id
   */
  public function plugOffCable( $charger_id )
  {
    
  }

  /**
   * Disconnect charger from voltage. 
   */
  public function shutdown( $charger_id )
  {
    //
  }

}