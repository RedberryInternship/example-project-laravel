<?php

namespace App\Library\Contracts;

interface MishasCharger
{
  /**
   * Get all the chargers info from Misha's back
   * 
   * @return array
   */
  public function all();

  /**
   * Get All the active chargers charger_id
   * 
   * @return array
   */
  public function getFreeChargersIds();

  /**
   * Find one charger in Misha's DB.
   * 
   * @param int $charger_id
   * @return object
   */
  public function find( $charger_id );

  /**
  * Find out if this specific charger is free
  * 
  * @param int $charger_id
  * @return bool 
  */
  public function isChargerFree( $charger_id );

  /**
   * Start Charging request to Misha's Back.
   * 
   * @param   int $charger_id
   * @param   int $connector_id
   * @return  string
   */
  public function start( $charger_id, $connector_id );

  /**
   * Stop Charging request to Misha's Back.
   * 
   * @param int $charger_id
   * @param int $transaction_id
   */
  public function stop( $charger_id, $transaction_id );


  /**
   * Get transaction info from Misha's DB.
   * 
   * @param int $id
   * @return object
   */
  public function transactionInfo( $id );
}