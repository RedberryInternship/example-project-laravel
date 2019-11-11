<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChargerTypesConnectorType extends Model
{
	protected $fillable = [
        'charger_charger_type_id',
        'connector_type_id'
    ];

    public function chargers()
    {
        return $this -> belongsTo('App/Charger','charger_types_connector_types');
    }

    public function tag()
    {
    	return $this -> belongsTo('App\Tag');
    }
}
