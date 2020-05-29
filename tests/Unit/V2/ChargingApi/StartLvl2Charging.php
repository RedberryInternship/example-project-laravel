<?php

namespace Tests\Unit\V2\ChargingApi;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

use Tests\Unit\V2\Stubs\Order as OStub;
use App\Facades\Simulator;
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
      Message;

  private $user;
  private $uri;
  private $url;

  protected function setUp(): void
  {
    parent::setUp();

    $this -> user   = factory( User :: class ) -> create();
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
    OStub :: makeOrder( $this -> user -> id, false );
      
    $orders_count   = Order::count();
    $kilowatt_count = Kilowatt::count();

    $this -> assertTrue( $orders_count > 0 );
    $this -> assertTrue( $kilowatt_count > 0 );
  }

  /** @test */
  public function when_order_is_created_status_is_INITIATED()
  {
    OStub :: makeOrder( $this -> user -> id, false );

    $chargerConnectorType = ChargerConnectorType::first();

    $response = $this 
      -> actAs( $this -> user ) 
      -> get(   $this -> uri .'active-orders' );

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
  }

  /** @test */
  public function lvl_2_charging_returns_valid_data()
  {
    Simulator :: upAndRunning( 29 );

    $charger              = factory( Charger :: class ) -> create([ 'charger_id' => 29 ]);
    $userCard             = factory( UserCard :: class) -> create();
    $chargerConnectorType = factory( ChargerConnectorType :: class ) -> create(
      [
        'charger_id'        => $charger -> id,
        'connector_type_id' => ConnectorType :: whereName( ConnectorTypeEnum :: TYPE_2 ) -> first(),
      ]
    );
    
    $response = $this 
      -> actAs( $this -> user )
      -> post(  $this -> url, 
        [
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
