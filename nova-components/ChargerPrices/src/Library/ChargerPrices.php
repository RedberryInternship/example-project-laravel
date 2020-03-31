<?php

namespace Redberry\Library\ChargerPrices;

class ChargerPrices
{
    public function getChargersConnectorTypes($chargers)
    {
        $connectorTypes = [];
        foreach ($chargers as $charger)
        {
            foreach ($charger['connector_types'] as $connectorType)
            {
                if ($connectorType['activeInput'])
                {
                    $connectorTypes[] = $connectorType['id'];
                }
            }
        }

        return $connectorTypes;
    }
}
