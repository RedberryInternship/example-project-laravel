<?php

namespace Tests\Unit\ChargingProcess;

use App\Charger;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

use App\Enums\ChargingType as ChargingTypeEnum;
use App\Enums\ConnectorType as ConnectorTypeEnum;
use App\Enums\OrderStatus as OrderStatusEnum;

use App\ChargerConnectorType;
use App\ConnectorType;
use App\Kilowatt;
use App\UserCard;
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
    $this -> create_order_with_charger_id_of_29();
    $charger_connector_type = ChargerConnectorType::first();

    $response = $this -> withHeader( 'Authorization', 'Bearer ' . $this -> token )
                      -> get( $this -> uri .'charging/status/' . $charger_connector_type -> id );

    $response = $response -> decodeResponseJson();

    $this -> assertEquals( OrderStatusEnum :: INITIATED, $response['payload']['status']);

    $this -> tear_down_order_data_with_charger_id_of_29();
  }

  /** @test */
  public function lvl_2_charging_returns_valid_data()
  {
    $this -> makeChargerFree();

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
