<?php

namespace App\Entities;

use App\Charger;
use App\Enums\ChargerStatus as ChargerStatusEnum;

trait BusinessChargerStatuses
{
    /**
     * Get Business Charger Statuses.
     * 
     * @param $connectorTypes - Array of Connector Types
     */
    public function businessChargerStatuses($connectorTypes = null)
    {
        $query = Charger::where('company_id', $this -> company_id);

        if ($connectorTypes && is_array($connectorTypes))
        {
            $query -> whereHas('connector_types', function($q) use ($connectorTypes) {
                $q -> whereIn('name', $connectorTypes);
            });
        }

        $active = (clone $query)
            -> where('status', ChargerStatusEnum :: ACTIVE )
            -> count();

        $inactive = (clone $query)
            -> where('status', ChargerStatusEnum :: INACTIVE )
            -> count();

        $charging = (clone $query)
            -> where('status', ChargerStatusEnum :: CHARGING )
            -> count();

        return [
            'Free'          => $active,
            'Charging'      => $charging,
            'Not Working'   => $inactive,
        ];
    }
}
