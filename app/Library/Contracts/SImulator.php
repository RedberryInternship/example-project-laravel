<?php

namespace App\Library\Contracts;

interface Simulator
{
  /**
   * Tell charger that it is Lvl 2 charger.
   * 
   * @param int $charger_id
   */
  public function activateSimulatorMode( $charger_id );

  /**
   * Give charger voltage.
   * 
   * @param int $charger_id
   */
  public function upAndRunning( $charger_id );

  /**
   * Plug connector cable of the charger.
   * 
   * @param int $charger_id
   */
  public function plugOffCable( $charger_id );

  /**
   * Disconnect charger from voltage.
   * 
   * @param int $charger_id
   */
  public function shutdown( $charger_id );
}