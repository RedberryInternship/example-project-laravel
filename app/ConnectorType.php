<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConnectorType extends Model
{
    protected $fillable = [
        'name',
        'old_id'
    ];

    // public function chargers()
    // {
    // 	return $this -> hasMany('App/Charger','charger_types_connector_types');
    // }
}
