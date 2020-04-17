<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ChargerTransaction;


class ChargerConnectorType extends Model
{
    /**
     * Guarded attributes parameter.
     * 
     * @var array $guarded
     */
    protected $guarded = [];

    /**
     * BelongsTo relationship with connector_types.
     * 
     * @return App\ConnectorType
     */
    public function connector_type()
    {
    	return $this -> belongsTo(ConnectorType::class);
    }

    /**
     * BelongsTo relationship with chargers.
     * 
     * @return App\Charger
     */
    public function charger()
    {
        return $this -> belongsTo(Charger::class);
    }


    /**
     * Get the newest charger transaction.
     * 
     * @return App\ChargerTransaction 
     */
    public function charger_transaction_first()
    {
        return ChargerTransaction::where('charger_id', $this -> charger_id)
            -> where('connector_type_id', $this -> connector_type_id)
            -> where('m_connector_type_id', $this -> m_connector_type_id)
            -> first();
    }

    /**
     * Get all the charger transactions
     * related to this ChargerConnectorType.
     * 
     * @return Illuminate\Database\Eloquent\Collection < App\ChargerTransactions >
     */
    public function charger_transaction_all()
    {
        return ChargerTransaction::where('charger_id', $this -> charger_id)
            -> where('connector_type_id', $this -> connector_type_id)
            -> where('m_connector_type_id', $this -> m_connector_type_id)
            -> get();
    }

}
