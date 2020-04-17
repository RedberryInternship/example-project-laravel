<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChargingPrice extends Model
{
    protected $fillable = [
        'charger_connector_type_id',
        'min_kwt',
        'max_kwt',
        'start_time',
        'end_time',
        'price'
    ];

    public function chargerConnectorType()
    {
    	return $this -> belongsTo('App\ChargerConnectorType');
    }
}
