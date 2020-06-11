<?php

namespace App\Library\Entities;

use App\Enums\ChargerType as ChargerTypeEnum;
use App\Enums\ConnectorType as ConnectorTypeEnum;

use App\Exceptions\NoSuchFastChargingPriceException;

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
   * Determine if charger is Fast.
   * 
   * @return bool
   */
  public function isChargerFast()
  {
    return $this -> determineChargerType() == ChargerTypeEnum :: FAST;
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

  /**
   * Get specific fast-charging price
   * from charger's fast-charging prices.
   * 
   * @param   int         $elapsedMinutes
   * @return  Collection
   */
  public function collectFastChargingPriceRanges( $elapsedMinutes )
  {
    $fastChargingPriceRanges  = $this 
      -> fast_charging_prices()
      -> where(
        [
          [ 'start_minutes' , '<=' , $elapsedMinutes ],
          [ 'end_minutes'   , '>=' , $elapsedMinutes ],
        ]
      )
      -> orWhere( 'end_minutes', '<', $elapsedMinutes )
      -> get();

    if( ! $fastChargingPriceRanges )
    {
        throw new NoSuchFastChargingPriceException();
    }

    return $fastChargingPriceRanges;
  }
}