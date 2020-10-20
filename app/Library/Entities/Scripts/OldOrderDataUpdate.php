<?php

namespace App\Library\Entities\Scripts;

use App\Library\Entities\DataImports\BoxwoodDataImport\DataGetter;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Order;

class OldOrderDataUpdate
{
  /**
   * Import orders.
   * 
   * @return void
   */
  public static function execute(): void
  {
    $orders = DataGetter :: get( 'active_transactions' );
    $formattedOrders = self :: format( $orders );
    self :: update($formattedOrders);
  }

  /**
   * Format orders data.
   * 
   * @param  array $orders
   * @return array
   */
  public static function format( $orders ): array
  {
    return array_map(function( $order ) {
      $createdAt = Carbon :: createFromDate($order -> date);

      return  (object) [
        'charge_power'            => round($order -> charge_power, 2),
        'consumed_kilowatts'      => round($order -> consumed_power / 1000, 2),
        'duration'                => round($order -> charge_time / (1000 * 60), 2),
        'charger_transaction_id'  => (int) $order -> charger_tr_id,
        'created_at'              => $createdAt,
      ];
    }, $orders);
  }

  /**
   * Update records.
   * 
   * 
   * @param array[object] $orders
   * @return void
   */
  public static function update(&$orders): void
  {
    $localOrders = self :: getOldOrders();

    array_walk($orders, function( &$order ) use(&$localOrders) {
      $foundOrder  = self :: findOrder($localOrders, $order -> charger_transaction_id);
      $foundOrder && $chargePrice = self :: getChargePrice($foundOrder -> price, $foundOrder -> target_price);

      $foundOrder && Order :: where('charger_transaction_id', $order -> charger_transaction_id )
        -> update(
          [
            'charge_power'       => $order -> charge_power,
            'consumed_kilowatts' => $order -> consumed_kilowatts,
            'duration'           => $order -> duration,
            'created_at'         => $order -> created_at,
            'start_date'         => $order -> created_at,
            'charge_price'       => $chargePrice,
            'company_id'         => $foundOrder -> company_id,
            'charger_name'       => json_decode($foundOrder -> name) -> ka,
            'address'            => json_decode($foundOrder -> location) -> ka,
          ]
        );
    });
  }

  /**
   * Get old orders.
   * 
   * @return object[]
   */
  private static function getOldOrders(): array
  {
    $columns = 'orders.id, orders.company_id, orders.user_card_id, orders.charging_type, orders.charger_transaction_id, '
      .'orders.price, orders.target_price, orders.charger_name, orders.start_date, orders.charge_price, orders.penalty_fee, '
      .'orders.duration, orders.charge_power, orders.consumed_kilowatts, orders.address, orders.created_at, chargers.name, '
      .'chargers.location, chargers.code, chargers.company_id';

    $query = 'select ' . $columns . ' from orders '
      .'left join charger_connector_types on orders.charger_connector_type_id = charger_connector_types.id '
      .'left join chargers on chargers.id = charger_connector_types.charger_id '
      .'where orders.old_id is not null ';

    return DB :: select($query);
  }

  /**
   * Find order.
   * 
   * @return object
   */
  private static function findOrder(array &$orders, string $chargerTransactionId )
  {
    $filteredElements = array_filter($orders, function($el) use( $chargerTransactionId ) {
      return $el -> charger_transaction_id == $chargerTransactionId;
    });

    return current( $filteredElements );
  }

  /**
   * Get charge price.
   * 
   * @param string $price
   * @param string $target_price
   * @return float
   */
  private static function getChargePrice(string $price, string $target_price): float
  {
    $price = floatval($price);
    $target_price = floatval($price);

    $result = $price > $target_price ? $price : $target_price;

    return round($result, 2);
  }
}