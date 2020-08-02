<?php

namespace App\Library\Entities\DataImports\BoxwoodDataImport;

use App\Enums\ChargingType as ChargingTypeEnum;
use App\Enums\OrderStatus as OrderStatusEnum;

use App\Charger;
use App\Order;
use App\User;

class ImportOrders
{
  /**
   * Import orders.
   * 
   * @return void
   */
  public static function execute(): void
  {
    $orders           = DataGetter :: get( 'orders' );
    $formattedOrders  = self :: format( $orders );    

    Order :: insert( $formattedOrders );
  }

  /**
   * Format orders data.
   * 
   * @param  array $orders
   * @return array
   */
  public static function format( $orders ): array
  {
    $chargerConnectorTypesDataBridge  = self :: chargerConnectorTypesDataBridge();
    $usersDataBridge                  = self :: usersDataBridge();
    
    $mappedOrders = array_map( function( $order ) use( $usersDataBridge, $chargerConnectorTypesDataBridge ) {
      $chargingStatusChangeDates        = json_encode([  OrderStatusEnum :: INITIATED => $order -> confirm_date ]);
      
      return [
        'old_id'                        =>  $order -> id,
        'user_id'                       =>  $usersDataBridge[ $order -> user_id ],
        'charging_type'                 =>  ChargingTypeEnum :: FULL_CHARGE,
        'charger_connector_type_id'     =>  @ $chargerConnectorTypesDataBridge[ $order -> charger_id ],
        'charger_transaction_id'        =>  ( int ) $order -> charger_transaction_id,
        'price'                         =>  $order -> price,
        'target_price'                  =>  $order -> target_price,
        'charging_status'               =>  OrderStatusEnum :: FINISHED,
        'charging_status_change_dates'  =>  $chargingStatusChangeDates,
      ];  
    }, $orders );

    return array_filter( $mappedOrders, function( $order ) {
      return !! $order[ 'charger_connector_type_id' ];
    });
  }

  /**
   * Users data bridge.
   * 
   * @return array
   */
  public static function usersDataBridge(): array
  {
    $usersDataBridge = [];

    foreach( User :: all() as $user )
    {
      $usersDataBridge[ $user -> old_id ] = $user -> id;
    }

    return $usersDataBridge;
  }

  /**
   * Charger and connector types data bridge.
   * 
   * @return array
   */
  public static function chargerConnectorTypesDataBridge(): array
  {
    $chargerConnectorTypesDataBridge = [];
    foreach( Charger :: with( 'charger_connector_types' ) -> get() as $charger )
    {
      $chargerConnectorTypesDataBridge[ $charger -> old_id ] = $charger -> charger_connector_types -> first() -> id;
    }

    return $chargerConnectorTypesDataBridge;
  }
}