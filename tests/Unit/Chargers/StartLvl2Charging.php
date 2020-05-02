<?php

namespace Tests\Unit\Chargers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

use App\Enums\ChargingType as ChargingTypeEnum;
use App\Enums\OrderStatus as OrderStatusEnum;

use App\ChargerConnectorType;
use App\Kilowatt;
use App\Order;

use App\Traits\Testing\Charger as ChargerTrait;
use App\Traits\Testing\User as UserTrait;
use App\Traits\Message;


class StartLvl2Charging extends TestCase {
  
  use RefreshDatabase,
      UserTrait,
      ChargerTrait,
      Message;

  private $token;
  private $uri;
  private $url;

  protected function setUp(): void
  {
    parent::setUp();

    $this -> token  = $this -> createUserAndReturnToken();
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
  public function start_charging_creates_new_order_record_with_kilowatt()
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
    $this -> create_order_with_charger_id_of_29();
    $charger_connector_type = ChargerConnectorType::first();

    $response = $this -> withHeader( 'Authorization', 'Bearer ' . $this -> token )
                      -> get( $this -> uri .'charging/status/' . $charger_connector_type -> id );

    $response = $response -> decodeResponseJson();

    $this -> assertEquals( OrderStatusEnum :: INITIATED, $response['payload']['status']);

    $this -> tear_down_order_data_with_charger_id_of_29();
  }

  /** @test */
  public function when_charger_is_not_free_dont_charge()
  {
    $this -> create_order_with_charger_id_of_29();

    $response = $this -> withHeader( 'token', 'Bearer ' . $this -> token)
      -> post($this -> uri . 'charging/start', [
        'charger_connector_type_id' => ChargerConnectorType::first() -> id,
        'charging_type'             => ChargingTypeEnum :: FULL_CHARGE,
      ]);

    $response = (object) $response -> decodeResponseJson();
    
    $this -> assertEquals( $this -> messages [ 'charger_is_not_free' ], $response -> message );

    $this -> tear_down_order_data_with_charger_id_of_29();
  }
}
