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

    /**
     * Present timestamps.
     */
    public $timestamps = true;

    /**
     * belongsTo relationship with ChargerConnectorType.
     * 
     * @return \App\ChargerConnectorType
     */
    public function charger_connector_type()
    {
    	return $this -> belongsTo( ChargerConnectorType :: class );
    }

    /**
     * Get connector type name.
     *
     * @return string|null
     */
    public function getConnector()
    {
        $connector = $this -> charger_connector_type;

        if($connector && $connector -> connector_type)
        {
            return $connector -> connector_type -> name;
        }

        return null;
    }
}

