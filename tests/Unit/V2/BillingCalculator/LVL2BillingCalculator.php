<?php

namespace Tests\Unit\V2\BillingCalculator;

use Tests\TestCase;

use App\Enums\ChargingType as CTEnum;
use App\Enums\PaymentType as PTEnum;
use App\Enums\OrderStatus as OSEnum;

use Tests\Unit\V2\Stubs\ChargerConnectorType as CCTStub;
use Tests\Unit\V2\Stubs\ChargingPrice as CPStub;

use App\User;
use App\Order;
use App\Config;
use App\Payment;
use Carbon\Carbon;

class LVL2BillingCalculator extends TestCase
{
  private $uri;
  private $active_orders_url;
  private $user;
  private $order;

  protected function setUp(): void
  {
    parent :: setUp();
    $this -> artisan( 'migrate:fresh' );

    $this -> uri               = config( 'app' )[ 'uri' ];
    $this -> active_orders_url = $this -> uri . 'active-orders';
    $this -> user              = factory( User   :: class ) -> create();
    $this -> createOrderWithPrerequisites();
  }

  /** @test */
  public function it_can_count_paid_money()
  {
    $order =  $this -> order;

    factory( Payment :: class ) -> create(
      [ 
        'order_id' => $order -> id,
        'type'      => PTEnum :: CUT,
        'price'     => 3.7125,  
      ]
    );
    
    factory( Payment :: class ) -> create(
      [ 
        'order_id' => $order -> id,
        'type'      => PTEnum :: CUT,
        'price'     => 5.8,  
      ]
    );
    
    factory( Payment :: class ) -> create(
      [ 
        'order_id' => $order -> id,
        'type'      => PTEnum :: FINE,
        'price'     => 120.9,  
      ]
    );

    $response = $this -> actAs( $this -> user ) -> get( $this -> active_orders_url );

    $response = ( object ) $response -> decodeResponseJson(0); 
   
    $this -> assertEquals( 9.51, $response -> already_paid );
  }

  /** @test */
  public function it_can_count_consumed_money_when_charging_with_lvl2_charger()
  {
    $order    = $this -> order;
    $kilowatt = $order -> kilowatt;
    
    // 2019 year, 10 march 00:00:00
    $now1 = Carbon :: create(2019, 3, 10, 0, 0, 0);
    $order -> updateChargingStatus( OSEnum :: CHARGING );

    Carbon :: setTestNow( $now1 );
    $payment  = factory( Payment :: class ) -> create(
      [
        'order_id'      => $order -> id,
        'confirm_date'  => now(),
        'type'          => PTEnum :: CUT,
        'price'         => 20,
      ]
    );

    // Case 1 
    $kilowatt -> update([ 'consumed' => 500, 'charging_power' => 1000 ]);

    $response = $this -> actAs( $this -> user ) -> get( $this -> active_orders_url );
    $response = ( object ) $response -> decodeResponseJson() [ 0 ] ;
    
    $this -> assertEquals( 47.5 , $response -> consumed_money );
    
    
    $kilowatt -> update([ 'consumed' => 5000 ]);
    $response = $this -> actAs( $this -> user ) -> get( $this -> active_orders_url );
    $response = ( object ) $response -> decodeResponseJson() [ 0 ] ;
    
    $this -> assertEquals( 475, $response -> consumed_money );
  }

  /** @test */
  public function it_can_count_penalty_fee()
  {
    $this       ->  artisan('db:seed --class=ConfigSeeder');
    $order      =   $this  -> order;
    $kilowatt   =   $order -> kilowatt;
    $config     =   Config :: first();
    $config     ->  penalty_price_per_minute = 0.6;
    $config     ->  save();

    $startTime  = Carbon :: create(2020, 3, 10, 12, 46, 7);
    $onPenalty  = Carbon :: create(2020, 3, 10, 12, 50, 10);
    $onFinish   = Carbon :: create(2020, 3, 10, 13, 10, 10); // 0.6 * 20 = 12

    Carbon :: setTestNow( $startTime );

    factory( Payment :: class ) -> create(
      [
        'order_id'      => $order -> id,
        'type'          => PTEnum :: CUT,
        'price'         => 20,
        'confirm_date'  => now(),
      ]
    );

    Carbon :: setTestNow( $onPenalty );
    $kilowatt -> update([ 'consumed' => 50 , 'charging_power' => 1000, ]);
    // 95 GEL / (50 / 1000) = 4.75

    $order    -> updateChargingStatus( OSEnum :: ON_FINE ); 

    Carbon :: setTestNow( $onFinish );

    $response = $this -> actAs( $this -> user ) -> get( $this -> active_orders_url );
    $response = ( object ) $response -> decodeResponseJson() [ 0 ];

    $this -> assertEquals(
      12,
      $response -> penalty_fee,
    );
  }

  /** @test */
  public function it_sets_penalty_start_time_accurately()
  {
    $this  -> artisan( 'db:seed --class=ConfigSeeder' );
    Config :: first() -> update([ 'penalty_relief_minutes' => 7 ]);
    $order =  $this -> order;
    $now   =  Carbon :: create( 2020, 11, 3, 17, 25, 10 );
    Carbon :: setTestNow( $now );

    $order -> update([ 'charging_type' => CTEnum :: BY_AMOUNT ]); 
    $order -> updateChargingStatus( OSEnum :: USED_UP );

    $response = $this     -> actAs( $this -> user ) -> get( $this -> active_orders_url );
    $response = $response -> decodeResponseJson() [ 0 ];

    $penaltyStartTime = Carbon :: createFromTimestamp( $response [ 'penalty_start_time' ] / 1000 );

    $this     -> assertEquals( $penaltyStartTime -> subMinutes( 7 ), $now );
  }

  /** Helpers */
  private function createOrderWithPrerequisites()
  {
    $chargerConnectorType = CCTStub :: createChargerConnectorType();

    CPStub :: createChargingPricesWithOnePhaseOfDay( $chargerConnectorType -> id );

    $this -> order             = factory( Order  :: class ) -> create(
      [ 
        'user_id'                   => $this -> user -> id,
        'charger_connector_type_id' => $chargerConnectorType -> id,
      ]
    );

    $this -> order -> kilowatt() -> create([ 'consumed' => 100, 'charging_power' => 1000 ]);
    $this -> order -> updateChargingStatus( OSEnum :: CHARGING );
  }
}