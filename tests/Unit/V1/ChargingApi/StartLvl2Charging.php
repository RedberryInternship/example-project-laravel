<?php

namespace Tests\Unit\ChargingApi;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

use Tests\Unit\V1\Traits\Helper;
use Tests\TestCase;

use App\Enums\ConnectorType as ConnectorTypeEnum;
use App\Enums\ChargingType as ChargingTypeEnum;
use App\Enums\OrderStatus as OrderStatusEnum;

use App\ChargerConnectorType;
use App\ConnectorType;
use App\Kilowatt;
use App\UserCard;
use App\Charger;
use App\Order;
use App\User;

use App\Traits\Message;

class StartLvl2Charging extends TestCase {
  
  use RefreshDatabase,
      Helper,
      Message;

  private $token;
  private $user;
  private $uri;
  private $url;

  protected function setUp(): void
  {
    parent::setUp();

    $this -> token  = $this -> create_user_and_return_token();
    $this -> user   = User :: first();
    $this -> uri    = config( 'app' )['uri'];
    $this -> url    = $this -> uri . 'charging/start';
  }

  protected function tearDown(): void
  {
    $this -> beforeApplicationDestroyed( function () {
      foreach(DB::getConnections() as $connection )
      {
        $connection -> disconnect();
      }
    });
    parent :: tearDown();
  }

  /** @test */
  public function it_creates_new_order_record_with_kilowatt()
  {
    $this -> create_order_with_charger_id_of_29();
      
    $orders_count   = Order::count();
    $kilowatt_count = Kilowatt::count();

    $this -> assertTrue( $orders_count > 0 );
    $this -> assertTrue( $kilowatt_count > 0 );

    $this -> tear_down_order_data_with_charger_id_of_29();
  }

  /** @test */
  public function when_order_is_created_status_is_INITIATED()
  {
    $this -> withExceptionHandling();

    $this -> create_order_with_charger_id_of_29( $this -> user -> id );
    $chargerConnectorType = ChargerConnectorType::first();

    $response = $this -> withHeader( 'Authorization', 'Bearer ' . $this -> token )
                      -> get( $this -> uri .'active-orders' );

    $response = $response -> decodeResponseJson();

    $desiredOrder    = null;
    foreach( $response as $order )
    {
      if( $order ['charger_connector_type_id'] == $chargerConnectorType -> id )
      {
        $desiredOrder = $order;
      }
    }
    
    $this -> assertEquals( OrderStatusEnum :: INITIATED, $desiredOrder[ 'charging_status' ]);

    $this -> tear_down_order_data_with_charger_id_of_29();
  }

  /** @test */
  public function lvl_2_charging_returns_valid_data()
  {
    $this -> make_charger_free();

    $charger              = factory( Charger :: class ) -> create([ 'charger_id' => 29 ]);
    $userCard             = factory( UserCard :: class) -> create();
    $chargerConnectorType = factory( ChargerConnectorType :: class ) -> create(
      [
        'charger_id'        => $charger -> id,
        'connector_type_id' => ConnectorType :: whereName( ConnectorTypeEnum :: TYPE_2 ) -> first(),
      ]
    );
    
    $response = $this -> withHeader( 'Authorization', 'Bearer ' . $this -> token )
      -> post( $this -> url, [
            'charger_connector_type_id' => $chargerConnectorType -> id,
            'charging_type'             => ChargingTypeEnum :: FULL_CHARGE,
            'user_card_id'              => $userCard -> id,
          ]
      );

    $response -> assertJsonStructure(
      [
      'already_paid',
      'consumed_money',
      'refund_money',
      'charging_status',
      'charger_connector_type_id',
      'charger_id',
      'connector_type_id',
      ]
    );
  }
}