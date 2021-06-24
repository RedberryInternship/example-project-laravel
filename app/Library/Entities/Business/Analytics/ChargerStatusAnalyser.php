<?php

namespace App\Library\Entities\Business\Analytics;

use App\Charger;
use App\Enums\ChargerStatus as ChargerStatusEnum;
use App\Enums\ConnectorType as ConnectorTypeEnum;

class ChargerStatusAnalyser
{
  /**
   * Analyse charger statuses.
   *
   * @return array
   */
  public static function analyse(): array
  {
    $lvl2Connectors = [ConnectorTypeEnum :: TYPE_2];
    $fastConnectors = [ConnectorTypeEnum :: COMBO_2, ConnectorTypeEnum :: CHADEMO];

    return [
      'lvl2'     => self :: businessChargerStatuses($lvl2Connectors),
      'fast'     => self :: businessChargerStatuses($fastConnectors),
      'labels'   => [
          __('business.dashboard.charger-statuses.charger.free'),
          __('business.dashboard.charger-statuses.charger.charging'),
          __('business.dashboard.charger-statuses.charger.not-working'),
      ],
      'statuses' => [ ChargerStatusEnum::ACTIVE, ChargerStatusEnum::CHARGING, ChargerStatusEnum::INACTIVE ],
    ];
  }

  /**
   * Get data depending on connector types.
   *
   * @return array
   */
  public static function businessChargerStatuses($connectorTypes = null): array
  {
    $user = auth() -> user();

      $query = Charger::where('company_id', $user -> company_id);

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

      return [ $active, $charging, $inactive ];
  }
}
