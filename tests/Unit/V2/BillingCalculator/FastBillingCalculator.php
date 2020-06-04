<?php

namespace Tests\Unit\V2\BillingCalculator;

use Tests\Unit\V2\Stubs\ChargerConnectorType as CCTStub;
use Tests\Unit\V2\Stubs\ChargingPrice as CPStub;
use App\Enums\ChargerType as ChargerTypeEnum;
use App\Enums\OrderStatus as OrderStatusEnum;
use App\Enums\PaymentType as PaymentTypeEnum;
use Tests\TestCase;
use Carbon\Carbon;
use App\Payment;
use App\Order;
use App\User;

class FastBillingCalculator extends TestCase
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
  public function it_can_count_consumed_money_when_charging_with_fast_charger()
  {
    $startChargingTime = Carbon :: create( 2020, 2, 17, 21, 40, 7 );
    Carbon :: setTestNow( $startChargingTime );

    $order = $this -> order;
    $order -> updateChargingStatus( OrderStatusEnum :: CHARGING );

    factory( Payment :: class ) -> create(
      [
        'order_id'      => $order -> id,
        'type'          => PaymentTypeEnum :: CUT,
        'confirm_date'  => now(),
        'price'         => 20,
      ]
    );

    // 10 minutes since charging started
    Carbon :: setTestNow( now() -> addMinutes(10) );
    $response       = $this -> actAs( $this -> user ) -> get( $this -> active_orders_url );
    $consumedMoney  = $response -> decodeResponseJson( 0 ) [ 'consumed_money' ];
    
    $this -> assertEquals( 10, $consumedMoney );
    
    // 12 minutes since charging started
    Carbon :: setTestNow( now() -> addMinutes(2) );
    $response       = $this -> actAs( $this -> user ) -> get( $this -> active_orders_url );
    $consumedMoney  = $response -> decodeResponseJson( 0 ) [ 'consumed_money' ];
    
    $this -> assertEquals( 14, $consumedMoney );
    
    // 20 minutes since charging started
    Carbon :: setTestNow( now() -> addMinutes(8) );
    $response       = $this -> actAs( $this -> user ) -> get( $this -> active_orders_url );
    $consumedMoney  = $response -> decodeResponseJson( 0 ) [ 'consumed_money' ];
    
    $this -> assertEquals( 30, $consumedMoney );
    
    // 25 minutes since charging started
    Carbon :: setTestNow( now() -> addMinutes(5) );
    $response       = $this -> actAs( $this -> user ) -> get( $this -> active_orders_url );
    $consumedMoney  = $response -> decodeResponseJson( 0 ) [ 'consumed_money' ];
    
    $this -> assertEquals( 55, $consumedMoney ); 
  }

  /** @test */
  public function it_returns_correct_data_when_fast_charging()
  {
    $startChargingTime = Carbon :: create(2020, 5, 7, 10, 12, 1);

    Carbon :: setTestNow( $startChargingTime );
    $order        = $this -> order;
    $order        -> updateChargingStatus( OrderStatusEnum :: CHARGING );
    $firstPayment = factory( Payment :: class ) -> create(
      [
        'order_id'        => $order -> id,
        'type'            => PaymentTypeEnum :: CUT,
        'price'           => '20.0',
      ]
    );
    
    // Case 1
    
    $startChargingTime -> addMinutes( 5 );

    $response = $this  -> actAs( $this -> user ) -> get( $this -> active_orders_url );
    $response = $response -> decodeResponseJson()[ 0 ];
    
    $this -> assertEquals( 20 , $response[ 'already_paid'   ]);
    $this -> assertEquals( 5  , $response[ 'consumed_money' ]);
    $this -> assertEquals( 15 , $response[ 'refund_money'   ]);
    
    // Case 2
    $startChargingTime -> addMinutes( 16 );

    factory( Payment :: class ) -> create(
      [
        'order_id'        => $order -> id,
        'type'            => PaymentTypeEnum :: CUT,
        'price'           => '20.0',
      ]
    );
    
    $response = $this     -> actAs( $this -> user ) -> get( $this -> active_orders_url );
    $response = $response -> decodeResponseJson() [ 0 ];

    $this -> assertEquals( $response[ 'already_paid'    ], 40 );
    $this -> assertEquals( $response[ 'consumed_money'  ], 35 );
    $this -> assertEquals( $response[ 'refund_money'    ], 5  );
  }

  /** helpers */
  public function createOrderWithPrerequisites()
  {
    $chargerConnectorType = CCTStub :: createChargerConnectorType( ChargerTypeEnum :: FAST );
    CPStub :: createFastChargingPrices( $chargerConnectorType -> id );

    $this -> order = factory( Order :: class ) -> create(
      [ 
        'charger_connector_type_id' => $chargerConnectorType -> id,
        'user_id'                   => $this -> user -> id,
      ]
    );
  }
  
}