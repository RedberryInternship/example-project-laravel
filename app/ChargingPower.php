<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChargingPower extends Model
{
    /**
     * Fillable fields.
     */
    protected $fillable = [
        'id',
        'order_id',
        "charging_power", 
        "tariffs_power_range",  
        "tariffs_daytime_range",  
        "tariff_price",   
        "start_at",       
        "end_at",
    ];

    /**
     * Disable default timestamps.
     */
    public $timestamps = false;

    /**
     * ChargingPower belongsTo order.
     */
    public function order() 
    {
        return $this -> belongsTo( Order :: class );
    }

    /**
     * Get charging power interval price.
     * 
     * @return float
     */
    public function getIntervalPrice()
    {
        $startedAt = (int) $this -> start_at;
        $endedAt = $this -> end_at !== null
            ? (int) $this -> end_at
            : now() -> timestamp;


        $diffMinutes = ($endedAt - $startedAt) / 60;

        return $diffMinutes * $this -> tariff_price;
    }
}