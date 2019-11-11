<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'charger_id',
        'user_id',
        'connector_type_id',
        'charging_type_id',
        'finished',
        'paid',
        'charge_fee',
        'charge_time'
    ];

    public function user()
    {
    	return $this -> belongsTo('App\User');
    }

    public function charger()
    {
    	return $this -> belongsTo('App\Charger');
    }

    public function connector_type()
    {
    	return $this -> belongsTo('App\ChargerConnectorType');
    }

    public function charging_type()
    {
    	return $this -> belongsTo('App\ChargingType');
    }
}
