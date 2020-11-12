<?php

namespace App\Library\Entities\Business\Analytics;

use Illuminate\Support\Facades\DB;
use App\Library\Entities\Helper;

class TopChargersAnalyser
{
  /**
   * Prepare analytical data.
   * 
   * @return array
   */
  public static function analyse(): array
  {
    $companyId = auth() -> user() -> company_id;

    $byOrders       = DB :: select(self :: ordersFrequencyQuery( $companyId ));
    $byKilowatts    = DB :: select(self :: accumulatedKilowattsPerChargerQuery( $companyId ));
    $byDuration     = DB :: select(self :: accumulatedDurationPerChargerQuery( $companyId ));
    
    Helper :: castListInto($byOrders, 'charge_count', 'int');
    Helper :: castListInto($byKilowatts, 'kilowatts', 'float');
    Helper :: castListInto($byDuration, 'duration', 'float');

    array_walk($byKilowatts, function( $el ) {
        $el -> kilowatts /= 1000;
        $el -> kilowatts = round($el -> kilowatts, 2);
    });

    return [
        'top_by_number_of_orders'   => $byOrders,
        'top_by_kilowatts'          => $byKilowatts,
        'top_by_duration'           => $byDuration,
    ];
  }

  /**
   * Query for orders frequency per charger.
   * 
   * @return string
   */
  private static function ordersFrequencyQuery( $companyId ): string
  {
    return 'select count(*) as charge_count, chargers.location, chargers.code from chargers '
        .'left join charger_connector_types on charger_connector_types.charger_id = chargers.id '
        .'left join orders on orders.charger_connector_type_id = charger_connector_types.id '
        .'where chargers.company_id = ' . $companyId . ' '
        .'group by chargers.code, chargers.location order by charge_count desc limit 10';
  }

  /**
   * Query for orders frequency per charger.
   * 
   * @return string
   */
  private static function accumulatedKilowattsPerChargerQuery( $companyId ): string
  {
    return 'select sum(kilowatts.consumed) as kilowatts, chargers.location, chargers.code from chargers '
        .'left join charger_connector_types on charger_connector_types.charger_id = chargers.id '
        .'left join orders on orders.charger_connector_type_id = charger_connector_types.id '
        .'left join kilowatts on kilowatts.order_id = orders.id '
        .'where chargers.company_id = ' . $companyId .' '
        .'group by chargers.code, chargers.location order by kilowatts desc limit 10';
  }

  /**
   * Accumulate duration frequency per charger.
   * 
   * @return string
   */
  private static function accumulatedDurationPerChargerQuery( $companyId ): string
  {
    return 'select sum(orders.duration) as duration, chargers.code, chargers.location from chargers '
        .'left join charger_connector_types on chargers.id = charger_connector_types.charger_id '
        .'left join orders on charger_connector_types.id = orders.charger_connector_type_id '
        .'where chargers.company_id = '. $companyId .' '
        .'group by chargers.code, chargers.location order by duration desc limit 10';
  }
}