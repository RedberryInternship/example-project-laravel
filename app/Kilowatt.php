<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kilowatt extends Model
{
    protected $guarded = [];
    
    protected $casts = [
        'consumed' => 'object'
    ];

    /**
     * Set charging power.
     * 
     * @param   float|string $chargingPower
     * @return  void
     */
    public function setChargingPower($chargingPower)
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

}
