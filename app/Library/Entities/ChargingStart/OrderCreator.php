<?php

namespace App\Library\Entities\ChargingStart;

use App\Library\DataStructures\ChargingStarter as ChargingStarterRequest;

use App\Order;

class OrderCreator
{
  /**
   * Create order resource.
   * 
   * @param   ChargingStarterRequest $requestModel
   * @return  Order
   */
  public static function create( ChargingStarterRequest $requestModel ): Order
  {
    $chargerConnectorTypeId = $requestModel -> getChargerConnectorTypeId();
    $chargingType           = $requestModel -> getChargingType();
    $userCardId             = $requestModel -> getUserCardId();
    $targetPrice            = $requestModel -> isChargingTypeByAmount() ? request() -> get( 'price' ) : null;

    return Order::create(
        [
            'charger_connector_type_id' => $chargerConnectorTypeId,
            'charging_status'           => null,
            'user_card_id'              => $userCardId,
            'user_id'                   => auth() -> user() -> id,
            'charging_type'             => $chargingType,
            'target_price'              => $targetPrice,
        ]
    );
  }
}