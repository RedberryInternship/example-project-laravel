<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChargerChargerType extends Model
{
    protected $fillable = [
        'charger_id',
        'charger_type_id'
    ];

    public function chargers()
    {
        return $this -> belongsTo('App/Charger');
    }
    
    public function connectorTypes() {
    	return $this -> hasMany('App/connectorTypes', 'charger_types_connector_types');
    }
}
