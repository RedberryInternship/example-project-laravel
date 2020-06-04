<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Library\Entities\ChargerConnectorType as Entity;

class ChargerConnectorType extends Model
{
    use Entity;
    
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
    
}
