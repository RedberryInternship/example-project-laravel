<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChargerType extends Model
{
    protected $fillable = [
        'name'
    ];

    public function chargers()
    {
    	return $this -> hasMany('App/Charger','charger_charger_types');
    }
}
