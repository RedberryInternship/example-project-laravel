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
        'consumed' => 'float'
    ];

    /**
     * Get Order, Kilowatt belongs to.
     */
    public function order()
    {
        return $this -> belongsTo( Order :: class );
    }

    /**
     * Set charging power.
     * 
     * @param   float|string $chargingPower
     * @return  void
     */
    public function setChargingPower( $chargingPower )
    {
        $this -> charging_power = (float) $chargingPower;
        $this -> save();
    }

    /**
     * Get charging power.
     * 
     * @return float
     */
    public function getChargingPower()
    {
        return (float) $this -> charging_power;
    }

    /**
     * Update consumed kilowatts.
     * 
     * @param float|integer $watts
     */
    public function updateConsumedKilowatts( $watts )
    {
        $kilowatts = $watts / 1000;
        $this -> update([ 'consumed' => $kilowatts ]);
    }
}
