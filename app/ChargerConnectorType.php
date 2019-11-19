<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChargerConnectorType extends Model
{
    protected $fillable = [
        'charger_id',
        'charger_type_id',
        'connector_type_id'
    ];


    // public function charger_type()
    // {
    // 	return $this -> belongsTo('App\ChargerType');
    // }

    // public function connector_type()
    // {
    // 	return $this -> belongsTo('App\ConnectorType');
    // }
}
