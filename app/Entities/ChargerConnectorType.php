<?php

namespace App\Entities;

use App\Enums\ChargerType as ChargerTypeEnum;
use App\Enums\ConnectorType as ConnectorTypeEnum;

use App\ChargingPrice;

trait ChargerConnectorType
{
  /**
   * Determine if this connector type 
   * makes charger Fast or Lvl2
   * 
   * @return string
   */
  public function determineChargerType()
  {
      if( ! isset( $this -> connector_type ))
      {
          $this -> load( 'connector_type' );
      }

      $connector_type = $this -> connector_type -> name;
      $fast           = [ ConnectorTypeEnum :: CHADEMO, ConnectorTypeEnum :: COMBO_2 ];
      
      return in_array( $connector_type, $fast ) ? ( ChargerTypeEnum :: FAST ) : ( ChargerTypeEnum :: LVL2 );
  }

  /**
   * Get specific charging type
   * from charger's charging prices.
   * 
   * @param int|float $chargingPower
   * @param string    $startChargingTime
   * 
   * @return \App\ChargingPrice
   */
  public function getSpecificChargingPrice( $chargingPower, $startChargingTime )
  {
    $rawSql         = $this -> getTimeBetweenSqlQuery( $startChargingTime );

    $chargingPrice  = $this 
      -> charging_prices() 
      -> where( 'min_kwt', '<=', $chargingPower )
      -> where( 'max_kwt', '>=', $chargingPower )
      -> whereRaw( $rawSql )
      -> first();

    return $chargingPrice;
  }

  /**
   * Get time between sql raw query.
   * 
   * @param   time $startChargingTime
   * @return  string
   */
  private function getTimeBetweenSqlQuery( $startChargingTime )
  {
    $rawSql = 'TIME( "'. $startChargingTime .'" )'
      .' BETWEEN TIME( start_time )'
      .' AND'
      .' TIME( end_time )';

    return $rawSql;
  }
}