<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChargerType extends Model
{
    protected $fillable = [
        'name'
    ];

    public function connector_types()
    {
    	return $this -> belongsTo('App/ConnectorType','charger_connector_types');
    }
}
