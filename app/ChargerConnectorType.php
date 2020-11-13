<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\NoSuchFastChargingPriceException;
use App\Enums\ConnectorType as ConnectorTypeEnum;
use App\Enums\ChargerType as ChargerTypeEnum;

use App\Library\Entities\ChargerConnectorType as Entity;

class ChargerConnectorType extends Model
{
    /**
     * Guarded attributes parameter.
     * 
     * @var array $guarded
     */
    protected $guarded = [];

    /**
     * BelongsTo relationship with connector_types.
     * 
     * @return App\ConnectorType
     */
    public function connector_type()
    {
    	return $this -> belongsTo( ConnectorType :: class );
    }

    /**
     * BelongsTo relationship with chargers.
     * 
     * @return App\Charger
     */
    public function charger()
    {
        return $this -> belongsTo( Charger :: class );
    }

    /** 
     * HasMany relationship with Order. 
     * 
     * @return Illuminate\Database\Eloquent\Collection 
     */
    public function orders()
    {
        return $this -> hasMany( Order :: class );
    }

    /**
     * hasMany relationship with ChargingPrice.
     * 
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function charging_prices()
    {
        return $this -> hasMany( ChargingPrice :: class );
    }

    /**
     * hasMany relationship with FastChargingPrice.
     * 
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function fast_charging_prices()
    {
        return $this -> hasMany( FastChargingPrice :: class );
    }

    /**
     * belongsTo relationship with Terminal.
     * 
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function terminal()
    {
        return $this -> belongsTo( Terminal :: class );
    }
    
    /**
     * Add scope for filtering active charger_connector_types.
     * 
     * @param Builder $query
     * @return Builder $query
     */
    public function scopeActive( $query ) 
    {
        $query -> whereStatus( 'active' );
    }

    /**
     * Determine if charger connector type is active.
     * 
     * @return boolean
     */
    public function isActive(): bool
    {
        return $this -> status === 'active';
    }

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
      -> where( 'min_kwt', '<=', intval($chargingPower) )
      -> where( 'max_kwt', '>=', intval($chargingPower) )
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
    $fastChargingPriceRanges  = FastChargingPrice::where( 'charger_connector_type_id', $this -> id )
      -> where(function($query) use ($elapsedMinutes) {
        return $query -> where(
          [
            [ 'start_minutes' , '<=' , $elapsedMinutes ],
            [ 'end_minutes'   , '>=' , $elapsedMinutes ],
          ]
        )
        -> orWhere( 'end_minutes', '<', $elapsedMinutes );
      })
      -> get();

    if( ! $fastChargingPriceRanges )
    {
        throw new NoSuchFastChargingPriceException();
    }

    return $fastChargingPriceRanges;
  }
}
