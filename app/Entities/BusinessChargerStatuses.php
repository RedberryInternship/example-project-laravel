<?php

namespace App\Entities;

use App\Charger;

trait BusinessChargerStatuses
{
    /**
     * Get Business Charger Statuses.
     * 
     * @param $connectorTypes - Array of Connector Types
     */
    public function businessChargerStatuses($connectorTypes = null)
    {
        $query = Charger::where('user_id', $this -> id);

        if ($connectorTypes && is_array($connectorTypes))
        {
            $query -> whereHas('connector_types', function($q) use ($connectorTypes) {
                $q -> whereIn('name', $connectorTypes);
            });
        }

        return [
            'active'   => (clone $query)
                     -> where('active', true)
                     -> count(),

            'inActive' => (clone $query)
                     -> where('active', false)
                     -> count(),

            'on'       => (clone $query)
                     -> where('active', true)
                     -> whereHas('charger_connector_types.orders', function($q) use ($connectorTypes) {
                        $q -> where('orders.charging_status', '!=', 'FINISHED');
                     })
                     -> count(),
        ];
    }
}
