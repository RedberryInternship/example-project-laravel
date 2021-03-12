<?php

namespace Tests\Unit;

use App\Order;
use App\Config;
use App\Charger;
use App\UserCard;
use Tests\TestCase;
use App\Enums\OrderStatus;
use App\Enums\ChargingType;
use App\ChargerConnectorType;

class Charging extends TestCase
{
  protected function setUp(): void
  {
    parent :: setUp();

    $this -> startChargingURL = $this -> uri . 'charging/start';
    $this -> stopChargingURL = $this -> uri . 'charging/stop';
    $this -> user = $this -> createUser();

    $this -> charger = factory( Charger :: class ) -> create();
    $this -> chargerConnectorType = factory( ChargerConnectorType :: class ) -> create(
      [
        'charger_id' => $this -> charger -> id,
        'connector_type_id' => 1,
      ]
    );

    $this -> userCard = factory( UserCard :: class ) -> create(
      [
        'user_id' => $this -> user -> id,
      ]
    );

    factory( Config :: class ) -> create();
  }

  /** @test */
  public function starts_with_ok(): void
  {
    $this
      -> actAs( $this -> user )
      -> post( $this -> startChargingURL,
        [
          'charger_connector_type_id' => $this -> chargerConnectorType -> id,
          'charging_type' => ChargingType :: FULL_CHARGE,
          'user_card_id'  => $this -> userCard -> id,
        ]
      )
      -> assertStatus(201)
      -> assertJsonFragment(
        [
          'charging_status' => OrderStatus :: INITIATED,
          'charging_type'   => ChargingType :: FULL_CHARGE,
        ]
      );
  }

  /** @test */
  public function start_charging_has_validation_errors(): void
  {
    $this
      -> actAs( $this -> user )
      -> post( $this -> startChargingURL,
        [
          'charging_type' => ChargingType :: FULL_CHARGE,
          'user_card_id'  => $this -> userCard -> id,
        ]
      )
      -> assertJsonValidationErrors(['charger_connector_type_id']);
  }
 
  /** @test */
  public function creates_new_order(): void
  {
    $oldOrderCount = Order :: count();

    $this
      -> actAs( $this -> user )
      -> post( $this -> startChargingURL,
        [
          'charger_connector_type_id' => $this -> chargerConnectorType -> id,
          'charging_type' => ChargingType :: BY_AMOUNT,
          'user_card_id'  => $this -> userCard -> id,
          'price' => 120,
        ]
      )
      -> assertStatus(201);

    $currentOrders = Order :: all();

    $this -> assertCount($oldOrderCount + 1, $currentOrders);
  }

  /** @test */
  public function finishes_order(): void
  {
    $order = factory( Order :: class ) -> create(
        [
          'charging_status' => OrderStatus :: CHARGING,
          'charger_connector_type_id' => $this -> chargerConnectorType -> id,
          'user_card_id' => $this -> userCard -> id,
          'user_id' => $this -> user -> id,
        ]
      );

    $this
      -> actAs( $this -> user )
      -> post( $this -> stopChargingURL ,
        [
          'order_id' => $order -> id,
        ]
      ) 
      -> assertOk()
      -> assertJsonFragment(
        [
          'charging_status' => OrderStatus :: CHARGED,
        ]
      );
  }

  /** @test */
  public function finish_charging_has_validation_errors(): void
  {
    $order = factory( Order :: class ) -> create(
        [
          'charging_status' => OrderStatus :: CHARGING,
          'charger_connector_type_id' => $this -> chargerConnectorType -> id,
          'user_card_id' => $this -> userCard -> id,
          'user_id' => $this -> user -> id,
        ]
      );

    $this
      -> actAs( $this -> user )
      -> post( 
        $this -> stopChargingURL ,
        [],
      ) 
      -> assertJsonValidationErrors(['order_id']);
  }
}