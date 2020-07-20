<?php

namespace App\Library\Entities\CheckOrders;

use App\Library\ResponseModels\RealChargerAttributes;
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
    $chargerId                  = $this -> chargerInfo -> getChargerId();
    $realChargerConnectorTypeId = $this -> chargerInfo -> getChargerConnectorTypeId();

    return Order :: with( 'charger_connector_type.charger' )
      -> whereIn( 'charging_status', $this -> orderStatuses())
      -> whereHas( 'charger_connector_type', function( $query ) use( $realChargerConnectorTypeId, $chargerId ) {
        $query -> where( 'm_connector_type_id', $realChargerConnectorTypeId );
        $query -> whereHas( 'charger', function( $query ) use ( $chargerId ) {
          $query -> where( 'charger_id', $chargerId );
        });
      })
      -> first();
  }

  /**
   * Kind of orders to find.
   * 
   * @return array
   */
  private function orderStatuses()
  {
    return [
      OrderStatusEnum :: NOT_CONFIRMED,
      OrderStatusEnum :: UNPLUGGED,
      OrderStatusEnum :: CANCELED,
    ];
  }
}