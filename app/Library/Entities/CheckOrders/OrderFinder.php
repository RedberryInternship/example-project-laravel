<?php

namespace App\Library\Entities\CheckOrders;

use App\Library\DataStructures\RealChargerAttributes;
use App\Enums\OrderStatus as OrderStatusEnum;
use App\Order;

class OrderFinder
{
  /**
   * charger information.
   * 
   * @var RealChargerAttributes $chargerInfo
   */
  private $chargerInfo;

  /**
   * Construct new instance with RealChargerAttributes.
   * 
   * @param RealChargerAttributes $chargerInfo
   */
  function __construct( RealChargerAttributes $chargerInfo )
  {
    $this -> chargerInfo = $chargerInfo;  
  }

  /**
   * Return new instance.
   * 
   * @param RealChargerAttributes $chargerInfo
   * @return self
   */
  public static function instance( RealChargerAttributes $chargerInfo ): self
  {
    return new self( $chargerInfo );
  }

  /**
   * Check orders and get abnormal ones.
   * 
   * @return Order|null
   */
  public function find()
  {
    $chargerId              = $this -> chargerInfo -> getChargerId();
    $realChargerConnectorId = $this -> chargerInfo -> getChargerConnectorTypeId();

    return Order :: with( 'charger_connector_type.charger' )
      -> where( function( $query ) {
        $query -> where  ( 'checked', false );
        $query -> orWhere( 'checked', null  );
      })
      -> whereNotIn( 'charging_status', $this -> finishedOrders() )
      -> whereHas  ( 'charger_connector_type', function( $query ) use( $realChargerConnectorId, $chargerId ) {
        $query -> where( 'm_connector_type_id', $realChargerConnectorId );
        $query -> whereHas( 'charger', function( $query ) use ( $chargerId ) {
          $query -> where( 'charger_id', $chargerId );
        });
      })
      -> first();
  }

  /**
   * Orders we don't care.
   * 
   * @return array
   */
  private function finishedOrders()
  {
    return [
      OrderStatusEnum :: FINISHED,
      OrderStatusEnum :: BANKRUPT,
      OrderStatusEnum :: PAYMENT_FAILED,
      OrderStatusEnum :: CANCELED,
      OrderStatusEnum :: UNPLUGGED,
    ];
  }
}