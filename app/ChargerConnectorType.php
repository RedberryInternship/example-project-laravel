<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Enums\ConnectorType as ConnectorTypeEnum;
use App\Enums\ChargerType;

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

        return in_array( $connector_type, $fast ) ? ( ChargerType :: FAST ) : ( ChargerType :: LVL2 );
    }
}
