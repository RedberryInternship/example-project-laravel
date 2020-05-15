<?php

namespace Tests\Unit\ChargingApi;

use Tests\TestCase;
use Tests\Traits\Helper;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Enums\ChargingType as ChargingTypeEnum;
use App\Enums\ConnectorType as ConnectorTypeEnum;

use App\User;
use App\Order;
use App\Charger;
use App\UserCard;
use App\ConnectorType;
use App\ChargerConnectorType;

class StartFastCharging extends TestCase
{
  use RefreshDatabase,
      Helper;

  private $start_charging_url;
  private $user;

  protected function setUp(): void
  {
    parent :: setUp();
    
    $this -> artisan( 'migrate:fresh' );
    $this -> uri                = config( 'app' )[ 'uri' ];
    $this -> start_charging_url = $this -> uri . 'charging/start';
    $this -> user               = factory( User::class ) -> create();
    $this -> withoutExceptionHandling();

    factory( UserCard :: class ) -> create(
      [
        'user_id' => $this -> user -> id,
      ]
    );
  }

  /** @test */
  public function it_creates_order_with_appropriate_charging_type()
  {
    $payload  = [
      'charging_type'             => ChargingTypeEnum :: BY_AMOUNT,
      'price'                     => 5.0,
    ];
   
    $this -> createOrderPrerequisites( $payload );

    $order = Order :: first();

    $this -> assertEquals( 1, Order :: count() );
    $this -> assertEquals( ChargingTypeEnum:: BY_AMOUNT, $order -> charging_type );
  }

  /** @test */
  public function it_pays_target_price_when_by_amount()
  {

    $payload  = [
      'charging_type'             => ChargingTypeEnum :: BY_AMOUNT,
      'price'                     => 5.0,
    ];
   
    $this -> createOrderPrerequisites( $payload );

    $order    = Order :: with( 'payments' ) -> first();
    $payment  = $order -> payments -> first();

    $this -> assertEquals( 5, $payment -> price );
  }

  

  public function createOrderPrerequisites( $payload )
  {
    $this -> make_charger_free();
    $user = $this -> user;
    $user -> load( 'user_cars' );
    $userCard = $this -> user -> user_cards -> first();

    $chargerConnectorType = factory( ChargerConnectorType :: class ) -> create(
      [
        'connector_type_id'   => ConnectorType :: whereName( ConnectorTypeEnum :: CHADEMO ) -> first() -> id,
        'm_connector_type_id' => 1,
        'charger_id'          => factory( Charger :: class ) -> create([ 'charger_id' => 29 ]),
      ]
    );

    $payload [ 'charger_connector_type_id'  ] = $chargerConnectorType -> id;
    $payload [ 'user_card_id'               ] = $userCard -> id;

    $resp = $this -> actAs( $user ) -> post( $this -> start_charging_url, $payload );
  }
}