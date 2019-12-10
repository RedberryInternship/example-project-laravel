<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FastChargingPrice extends Model
{
    protected $fillable = [
        'charger_id',
        'start_minutes',
        'end_minutes',
        'price'
    ];

    public function charger()
    {
    	return $this -> belongsTo('App\Charger');
    }
}
