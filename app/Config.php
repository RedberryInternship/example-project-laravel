<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    /**
     * Fillable Fields.
     */
    protected $fillable = [
        'initial_charging_price',
        'next_charging_price',
        'penalty_relief_minutes',
        'penalty_price_per_minute',
    ];

    /**
     * Initial charge price.
     * 
     * @return float
     */
    public static function initialChargePrice()
    {
        return self :: first() -> initial_charging_price;
    }

    /**
     * Next charge price.
     * 
     * @return float
     */
    public static function nextChargePrice()
    {
        return self :: first() -> next_charging_price;
    }
}
