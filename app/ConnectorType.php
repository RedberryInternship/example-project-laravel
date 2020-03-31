<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConnectorType extends Model
{
    protected $fillable = [
        'name',
        'old_id'
    ];

    public function chargers()
    {
	  return $this
				-> belongsToMany('App\Charger', 'charger_connector_types')
				-> withPivot([
					'min_price',
        			'max_price',
					'charger_type_id'
                ]);
    }

    public function charger_types()
    {
	  return $this
				-> belongsToMany('App\ChargerType', 'charger_connector_types')
				-> withPivot([
					'min_price',
        			'max_price',
					'charger_type_id'
				]);
    }
}
