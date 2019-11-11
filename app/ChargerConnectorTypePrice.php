<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChargerConnectorTypePrice extends Model
{
    protected $fillable = [
        'charger_connector_type_id',
        'price',
        'start_time',
        'end_time'
    ];

    public function charger_connector_type()
    {
    	return $this -> belongsTo('App\ChargerConnectorType');
    }
}
