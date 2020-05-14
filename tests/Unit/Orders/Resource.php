<?php

namespace Tests\Unit\Orders;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

use Tests\Traits\Helper;
use Tests\TestCase;

use App\Enums\ConnectorType as ConnectorTypeEnum;
use App\Enums\ChargingType as ChargingTypeEnum;
use App\Enums\OrderStatus as OrderStatusEnum;
use App\Enums\PaymentType as PaymentTypeEnum;

use App\ChargerConnectorType;
use App\ChargingPrice;
use App\ConnectorType;
use App\Payment;
use App\Charger;
use App\Order;
use App\User;
use Carbon\Carbon;

class Resource extends TestCase
{
  use RefreshDatabase,
      Helper;
  
  private $user;
  private $token;
  private $uri;
  private $active_orders_url;
  private $order_url;
  
  protected function setUp(): void
  {
    parent :: setUp();

    $this -> token    = $this -> create_user_and_return_token();
    $this -> user     = User :: first();
    $this -> uri      = config()[ 'app.uri' ];
    $this -> active_orders_url      = $this -> uri . 'active-orders';
    $this -> order_url = $this -> uri . 'order';
    $this -> request  = $this -> withHeader('Authorization', 'Bearer ' . $this -> token ); 
  }

  protected function tearDown(): void
  {
    $this -> beforeApplicationDestroyed( function (){
      $connections = DB :: getConnections();
      foreach( $connections as $connection )
      {
        $connection -> disconnect();
      }
    });
    parent :: tearDown();
  }

  /** @test */
  public function active_orders_response_is_ok()
  {
    $response = $this -> request -> get( $this -> active_orders_url );
    $response -> assertOk();
  }

  /** @test */
  public function it_returns_active_orders()
  {
    $user = $this -> user;
    factory( Order :: class )     -> create(
      [ 
        'user_id' => $user -> id, 
        'charging_status' => OrderStatusEnum :: INITIATED,
      ]
    );

    factory( Order :: class )     -> create(
      [ 
        'user_id' => $user -> id, 
        'charging_status' => OrderStatusEnum :: CHARGING, 
      ]
    );

    factory( Order :: class, 3 )  -> create(
      [ 
        'user_id' => $user -> id, 
        'charging_status' => OrderStatusEnum :: FINISHED, 
      ]
    );

    $response = $this -> request -> get( $this -> active_orders_url );
    $response = $response -> decodeResponseJson();

    $this -> assertCount( 2, $response );
  }

  /** @test */
  public function it_returns_correct_data_when_fast_charging()
  {

    $connectorType = ConnectorType :: whereName( ConnectorTypeEnum :: CHADEMO ) -> first();
    
    $chargerConnectorType = factory( ChargerConnectorType :: class ) -> create(
      [
        'connector_type_id' => $connectorType -> id,
      ]
    );

    $this -> make_fast_charging_prices( $chargerConnectorType -> id );

    $startChargingTime = Carbon :: create(2020, 5, 7, 10, 12, 1);

    Carbon :: setTestNow( $startChargingTime );

    $order = factory( Order :: class ) -> create(
      [ 
        'user_id'                   => $this -> user -> id, 
        'charging_status'           => OrderStatusEnum :: CHARGING,
        'charger_connector_type_id' => $chargerConnectorType -> id, 
      ]
    );


    $firstPayment = factory( Payment :: class ) -> create(
      [
        'order_id'        => $order -> id,
        'type'            => PaymentTypeEnum :: CUT,
        'price'           => '20.0',
      ]
    );
    
    // Case 1
    
    $startChargingTime -> addMinutes( 5 );

    $response = $this  -> actAs( $this -> user ) -> request -> get( $this -> active_orders_url );
    $response = $response -> decodeResponseJson() [ 0 ];
    
    $this -> assertEquals( $response[ 'already_paid'   ]  , 20  );
    $this -> assertEquals( $response[ 'consumed_money' ]  , 5   );
    $this -> assertEquals( $response[ 'refund_money'   ]  , 15  );
    
    // Case 2
    $startChargingTime -> addMinutes( 16 );

    factory( Payment :: class ) -> create(
      [
        'order_id'        => $order -> id,
        'type'            => PaymentTypeEnum :: CUT,
        'price'           => '20.0',
      ]
    );
    
    $response = $this     -> request -> get( $this -> active_orders_url );
    $response = $response -> decodeResponseJson() [ 0 ];

    $this -> assertEquals( $response[ 'already_paid'    ], 40 );
    $this -> assertEquals( $response[ 'consumed_money'  ], 35 );
    $this -> assertEquals( $response[ 'refund_money'    ], 5  );
  }


  /** @test */
  public function it_returns_correct_data_when_lvl_2_charging()
  {
    $connectorType = ConnectorType :: whereName( ConnectorTypeEnum :: TYPE_2 ) -> first();
    $chargerConnectorType = factory( ChargerConnectorType :: class ) -> create(
      [
        'connector_type_id' => $connectorType -> id,
      ]
    );
    
    factory( ChargingPrice :: class ) -> create(
      [
        'min_kwt'                   => 0,
        'max_kwt'                   => 5,
        'start_time'                => '00:00',
        'end_time'                  => '24:00',
        'price'                     => 5,
        'charger_connector_type_id' => $chargerConnectorType -> id,
      ]
    );

    factory( ChargingPrice :: class ) -> create(
      [
        'min_kwt'                   => 6,
        'max_kwt'                   => 20,
        'start_time'                => '00:00',
        'end_time'                  => '24:00',
        'price'                     => 15,
        'charger_connector_type_id' => $chargerConnectorType -> id,
      ]
    );

    factory( ChargingPrice :: class ) -> create(
      [
        'min_kwt'                   => 21,
        'max_kwt'                   => 1000000,
        'start_time'                => '00:00',
        'end_time'                  => '24:00',
        'price'                     => 50,
        'charger_connector_type_id' => $chargerConnectorType -> id,
      ]
    );

    $order = factory( Order :: class ) -> create(
      [
        'user_id'                   => $this -> user -> id,
        'charging_status'           => OrderStatusEnum :: CHARGING,
        'charger_connector_type_id' => $chargerConnectorType -> id,
      ]
    );

    $firstPayment = factory( Payment :: class ) -> create(
      [
        'order_id'  => $order -> id,
        'type'      => PaymentTypeEnum :: CUT,
        'price'     => 20,
      ]
    );

    // Case 1
    $kilowatt = $order -> kilowatt() -> create([ 'consumed' => 150, 'charging_power' => 7 ]);

    $response = $this -> request -> get( $this -> active_orders_url );
    $response = $response -> decodeResponseJson() [ 0 ];

    $this -> assertEquals( $response[ 'consumed_money' ], 321.43 );
    $this -> assertEquals( $response[ 'already_paid' ]  , 20     );


    // Case 2
    $kilowatt =   $order -> kilowatt;
    $kilowatt ->  charging_power = 22;
    $kilowatt ->  save();

    $kilowatt -> update([ 'consumed' => 44]);

    $response = $this -> request -> get( $this -> active_orders_url );
    $response = $response -> decodeResponseJson() [ 0 ];
    
    $this -> assertEquals( $response[ 'consumed_money' ], 100 );
    $this -> assertEquals( $response[ 'already_paid' ]  , 20     );
  }

  /** @test */
  public function it_can_get_one_order()
  {
    $charger              = factory( Charger :: class ) -> create();
    $chargerConnectorType = factory( ChargerConnectorType :: class ) -> create(
      [ 
        'charger_id'                => $charger -> id,
      ]
    );
    
    $order                = factory( Order :: class ) -> create(
      [
        'charger_connector_type_id' => $chargerConnectorType -> id,
      ]
    );

    $response = $this -> withHeader( 'Authorization', 'Bearer ' . $this -> token )
                      -> get( $this -> order_url . '/'. $order -> id );

    $response -> assertJsonStructure(
      [
        'consumed_money',
        'already_paid',
        'refund_money',
        'charging_type',
        'charger_connector_type_id',
        'connector_type_id',
        'charger_id',
        'user_card_id',
      ]
    );
  }

  /** @test */
  public function it_sets_penalty_relief_start_time_when_entered_penalty_relief_mode()
  {
    $user                 = $this -> user;

    $chargerConnectorType = factory( ChargerConnectorType :: class ) -> create(
      [
        'connector_type_id' => ConnectorType :: first() -> id,
      ]
    );

    $order                = factory( Order :: class ) -> create(
      [
        'charger_connector_type_id' => $chargerConnectorType -> id,
        'user_id'                   => $user -> id,
        'charging_type'             => ChargingTypeEnum :: BY_AMOUNT,
      ]
    );

    $now = Carbon :: create( 2020, 11, 3, 17, 25, 10 );
    Carbon :: setTestNow( $now );


    $order -> updateChargingStatus( OrderStatusEnum :: USED_UP );

    $response = $this -> actAs( $user ) -> get( $this -> active_orders_url );
    $response = $response -> decodeResponseJson() [ 0 ];

    $this -> assertTrue( !! $response [ 'penalty_relief_mode_start_time' ]);
  }
}