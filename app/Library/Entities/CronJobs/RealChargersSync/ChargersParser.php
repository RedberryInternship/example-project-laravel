<?php

namespace App\Library\Entities\CronJobs\RealChargersSync;

use App\Enums\ChargerStatus as ChargerStatusEnum;

class ChargersParser
{
  /**
   * Parse all the chargers.
   * 
   * @param  array
   * @return array
   */
  public static function parseAll( $realChargers )
  {
    return self :: arrangeChargers( $realChargers );
  }

  /**
   * Parse single charger.
   * 
   * @param  object
   * @return array
   */
  public static function parseOne( $realCharger )
  {
    return self :: transformRealChargerIntoArray( $realCharger );
  }

  /**
   * Structure transformed real charger 
   * objects into array of the insertable
   * records.
   * 
   * @param array<object> $realChargers
   * @return array<array>
   */
  private static function arrangeChargers( $realChargers )
  {
    return array_map( function ( $realCharger ) {
      return self :: transformRealChargerIntoArray( $realCharger );
    }, 
    $realChargers );
  } 

  /**
   * Transform real charger object into 
   * insertable/updatable
   * charger record.
   * 
   * @param object $realCharger
   * @return array
   */
  private static function transformRealChargerIntoArray( $realCharger )
  {  
    return [
      'charger_id'  => (int) $realCharger -> id,
      'code'        => $realCharger -> code,
      #'description' => $realCharger -> description,
      'description' => [
        'en' => '---',
        'ka' => '---',
        'ru' => '---',
      ],
      'lat'         => $realCharger -> latitude,
      'lng'         => $realCharger -> longitude,
      'connectors'  => $realCharger -> connectors,
      'status'      => self :: getStatus( $realCharger -> status ),
    ]; 
  }

  /**
   * Get charger status.
   * 
   * @param   int $statusCode
   * @return  string 
   */
  private static function getStatus( $statusCode )
  {
    switch( $statusCode )
    {
      case -1: return ChargerStatusEnum :: INACTIVE;
      case  0: return ChargerStatusEnum :: ACTIVE;
      case  1: return ChargerStatusEnum :: CHARGING;
    }
  }
}