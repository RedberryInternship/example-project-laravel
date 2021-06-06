<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FastChargingPrice extends Model
{
    protected $fillable = [
        'charger_connector_type_id',
        'start_minutes',
        'end_minutes',
        'price'
    ];

    /**
     * BelongsTo relationship with ChargerConnectorType.
     * 
     * @return \App\ChargerConnectorType
     */
    public function charger_connector_type()
    {
    	return $this -> belongsTo( ChargerConnectorType :: class );
    }

    /**
     * Get charger connector type name.
     *
     * @return string
     */
    public function connectorType()
    {
        $connector = $this->charger_connector_type;

        if($connector && $connector -> connector_type)
        {
            return $connector -> connector_type -> name;
        }

        return null;
    }
}

