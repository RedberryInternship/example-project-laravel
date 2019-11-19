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
    	return $this -> belongsToMany('App/ConnectorType', 'charger_connector_types') -> withPivot('charger_type_id');
    }

    public function chargers()
    {
    	return $this -> belongsToMany('App/Charger', 'charger_connector_types') -> withPivot('charger_type_id');
    }

    public static function getChargerTypesKeyValueArray()
    {
        $chargerTypesSelect = [];
        $chargerTypes       = ChargerType::all() -> keyBy('id');
        foreach ($chargerTypes as $chargerTypeID => $chargerType)
        {
            $chargerTypesSelect[$chargerTypeID] = $chargerType -> name;
        }

        return $chargerTypesSelect;
    }
}
