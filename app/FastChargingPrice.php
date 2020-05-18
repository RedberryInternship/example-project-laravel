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
}

