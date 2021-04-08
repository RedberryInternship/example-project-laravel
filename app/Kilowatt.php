<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kilowatt extends Model
{
    /**
     * Laravel guarder attribute.
     */
    protected $guarded = [];
    
    /**
     * Laravel casts attribute.
     */
    protected $casts = [
        'consumed' => 'float',
        'charging_power' => 'float',
    ];

    /**
     * Get Order, Kilowatt belongs to.
     */
    public function order()
    {
        return $this -> belongsTo( Order :: class );
    }

    /**
     * Get charging power.
     * 
     * @return float
     */
    public function getChargingPower()
    {
        return $this -> charging_power;
    }
}
