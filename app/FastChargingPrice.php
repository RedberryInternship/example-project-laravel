<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FastChargingPrice extends Model
{
    protected $fillable = [
        'charger_connector_type_id',
        'start_minutes',
        'end_minutes',
        'price'
    ];

    public function chargerConnectorType()
    {
    	return $this -> belongsTo('App\ChargerConnectorType');
    }
}

