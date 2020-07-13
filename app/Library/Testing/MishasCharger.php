<?php

namespace App\Library\Testing;

use App\Enums\MishasChargerStatus as MishasChargerStatusEnum;
use App\Facades\MockSyncer;

class MishasCharger
{
  /**
   * all chargers.
   * 
   * @var array $chargers
   */
  private $chargers          = [];

  /**
   * Currently charging.
   * 
   * @var array $currentlyCharging
   */
  private $currentlyCharging = [];

  /** 
   * Set chargers.
   */
  public function __construct()
  {
    $this -> chargers = MockSyncer :: generateMockChargers( 30 );   

    $chargerWithIdOf29 = array_filter( $this -> chargers, function( $item ) {
      return $item -> id == 29;
    });

    if( ! $chargerWithIdOf29 )
    {
      $this -> chargers [0] -> id = 29;
    }
  }

  /**
   * Get all chargers;
   * 
   * @return array
   */
  public function all(): array
  {
    return $this -> chargers;
  }

  /**
   * Set chargers.
   * helper.
   * 
   * @param array $chargers
   */
  public function setChargers( $chargers )
  {
    $this -> chargers = $chargers;
  }

  /**
   * Get all free chargers ids.
   * 
   * @return array
   */
  public function getFreeChargersIds(): array
  {
    $free_chargers_ids = [];
    $all_chargers_info = $this -> all();

    foreach($all_chargers_info as $single_charger_info)
    {
        if($single_charger_info -> status == MishasChargerStatusEnum :: FREE)
        {
            $free_chargers_ids []= $single_charger_info -> id;
        }
    }
    
    return $free_chargers_ids;
  }
  
  /**
   * Find specific charger.
   * 
   * @param   int $charger_id
   * @return  object
   */
  public function find($charger_id)
  {
    $charger = null;

    foreach( $this -> chargers as $each )
    {
      if( $each -> id == $charger_id)
      {
        $charger = $each;
      }
    }
    return $charger;
  }

  /**
   * Determine if specific charger is free.
   * 
   * @param   integer $charger_id
   * @return  bool
   */
  public function isChargerFree($charger_id): bool
  {
    $charger = $this -> find( $charger_id );

    if( ! $charger )
    {
      return false;
    }

    return $charger -> status == MishasChargerStatusEnum :: FREE;
  }

  /**
   * start charging.
   * 
   * @param   integer $charger_id
   * @param   integer $connector_id
   * @return  string
   */
  public function start($charger_id, $connector_id): string
  {

      foreach( $this -> chargers as $charger )
      {
        if( $charger_id == $charger -> id )
        {
          $charger -> status = MishasChargerStatusEnum :: CHARGING;
        }
      }

      $charger_transaction_id = ( string ) random_int( 10000, 100000 );
      $currentlyCharging [ $charger_id ] = $charger_transaction_id;

      return $charger_transaction_id;
  }

  /**
   * Stop charging.
   * 
   * @param $charger_id
   */
  public function stop($charger_id, $transaction_id)
  {
    $currentlyCharging     = $this -> currentlyCharging;
    $newCurrentlyCharging  = [];
    foreach( $currentlyCharging as $key => $each )
    {
      if( $each != $transaction_id )
      {
        $newCurrentlyCharging [$key] = $each;
      }
    }

    $this -> currentlyCharging = $newCurrentlyCharging;
  }

  /**
   * Get Transaction info.
   */
  public function transactionInfo( $id ): object
  {
    $transaction_info = [
      "id"              => 74692,
      "chargePointName" => "espace-0032",
      "chargePointCode" => "0028",
      "version"         => 27,
      "uuidStart"       => "uuid:7e7b1c99-f1da-4f74-a5a8-d8152d1bf18d",
      "uuidEnd"         => "uuid:be995989-5ec8-4173-ad1f-3c3c85fc5c5f",
      "connectorId"     => 1,
      "transStart"      => 1587474081167,
      "transStop"       => 1587475016513,
      "meterStart"      => 202662717,
      "meterStop"       => 202971342,
      "chargingTime"    => 935346,
      "kiloWattHour"    => 1234.5,
      "consumed"        => 0
    ];

    return ( object ) $transaction_info;
  }
}