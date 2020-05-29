<?php

namespace Tests\Unit\V2\Stubs;

use Tests\Unit\V2\Stubs\ChargerConnectorType as CCTStub;
use App\Enums\ChargerType as ChargerTypeEnum;
use App\Order as OrderModel;
use App\Facades\Simulator;
use App\UserCard;
use App\Kilowatt;

class Order
{
  public static function makeOrder( int $user_id, bool $isChargerFast = true )
  {

    $userCard = factory( UserCard :: class ) -> create(
      [
        'user_id' => $user_id,
      ]
    );

    $chargerType = $isChargerFast 
      ? ChargerTypeEnum :: FAST
      : ChargerTypeEnum :: LVL2;

    $chargerConnectorType = CCTStub :: createChargerConnectorType( $chargerType );

    Simulator :: upAndRunning( 29 );

    $order = factory( OrderModel :: class ) -> create(
      [
        'user_id'                   => $user_id,
        'charger_connector_type_id' => $chargerConnectorType -> id,
        'user_card_id'              => $userCard -> id,
      ]
    );

    factory( Kilowatt :: class ) -> create( 
      [
        'order_id'        => $order -> id,
        'consumed'        => 0,
        'charging_power'  => 0,
      ]
    );

    return $order;
  }
}