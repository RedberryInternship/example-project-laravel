<?php

namespace Tests\Unit\Orders;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

use Tests\Traits\Helper;
use Tests\TestCase;

use App\Enums\ConnectorType as ConnectorTypeEnum;
use App\Enums\PaymentType as PaymentTypeEnum;
use App\Enums\OrderStatus as OrderStatusEnum;


use App\ChargerConnectorType;
use App\FastChargingPrice;
use App\ConnectorType;
use App\Payment;
use App\Config;
use App\Order;
use App\User;

class OrderWithPricing extends TestCase
{
  use RefreshDatabase,
      Helper;

  private $user;
  private $order;

  protected function setUp(): void
  {
    parent::setUp();

    $this -> user   = factory( User :: class ) -> create();
    $this -> order  = factory( Order :: class ) -> create([ 'user_id' => $this -> user -> id ]);
  }

  /** @test */
  public function order_can_count_paid_money()
  {
    $order = $this -> order;
    factory( Payment :: class ) -> create(
      [ 
        'order_id' => $order -> id,
        'type'      => PaymentTypeEnum :: CUT,
        'price'     => 3.7125,  
      ]
    );
    
    factory( Payment :: class ) -> create(
      [ 
        'order_id' => $order -> id,
        'type'      => PaymentTypeEnum :: CUT,
        'price'     => 5.8,  
      ]
    );
    
    factory( Payment :: class ) -> create(
      [ 
        'order_id' => $order -> id,
        'type'      => PaymentTypeEnum :: FINE,
        'price'     => 120.9,  
      ]
    );

    $order -> load( 'payments' );
    $paidMoney = $order -> countPaidMoney();

    $this -> assertEquals( 9.51, $paidMoney );
  }

  /** @test */
  public function order_can_count_paid_money_with_fine()
  {
    $order = $this -> order;
    factory( Payment :: class ) -> create(
      [ 
        'order_id' => $order -> id,
        'type'      => PaymentTypeEnum :: CUT,
        'price'     => 3.7125,  
      ]
    );
    
    factory( Payment :: class ) -> create(
      [ 
        'order_id' => $order -> id,
        'type'      => PaymentTypeEnum :: CUT,
        'price'     => 5.8,  
      ]
    );
    
     factory( Payment :: class ) -> create(
      [ 
        'order_id' => $order -> id,
        'type'      => PaymentTypeEnum :: FINE,
        'price'     => 120.9,  
      ]
    );

    $paidMoney = $order -> countPaidMoneyWithFine();

    $this -> assertEquals( 130.41, $paidMoney );
  }

  /** @test */
  public function order_can_count_consumed_money_when_charging_with_fast_charger()
  {
    $connectorType = ConnectorType :: whereName( ConnectorTypeEnum :: CHADEMO ) -> first();
    $chargerConnectorType = factory( ChargerConnectorType :: class ) -> create(
      [
        'connector_type_id' => $connectorType -> id,
      ]
    );

    factory( FastChargingPrice :: class ) -> create(
      [
        'start_minutes' => 0,
        'end_minutes'   => 20,
        'price'         => 100,
        'charger_connector_type_id' => $chargerConnectorType -> id, 
      ]
    );

    factory( FastChargingPrice :: class ) -> create(
      [
        'start_minutes' => 20,
        'end_minutes'   => 50,
        'price'         => 130,
        'charger_connector_type_id' => $chargerConnectorType -> id, 
      ]
    );

    factory( FastChargingPrice :: class ) -> create(
      [
        'start_minutes' => 50,
        'end_minutes'   => 1000000,
        'price'         => 200,
        'charger_connector_type_id' => $chargerConnectorType -> id, 
      ]
    );

    $order = factory( Order :: class ) -> create(
      [ 
        'charger_connector_type_id' => $chargerConnectorType -> id 
      ]
    );

    $startTime  = now() -> subMinutes( 15 ); 
    $startTime2 = now() -> subMinutes( 25 );
    $startTime3 = now() -> subMinutes( 160 );

    // Case 1
    $payment = factory( Payment :: class ) -> create(
      [
        'order_id'     => $order -> id,
        'confirm_date' => $startTime,
      ]
    );

    $consumedMoney = $order -> countConsumedMoney();
    $this -> assertEquals( 100, $consumedMoney );
    
    // Case 2
    $payment -> confirm_date = $startTime2;
    $payment -> save();

    $consumedMoney = $order -> countConsumedMoney();
    $this -> assertEquals( 130, $consumedMoney );
    
    // Case 3
    $payment -> confirm_date = $startTime3;
    $payment -> save();

    $consumedMoney = $order -> countConsumedMoney();
    $this -> assertEquals( 200, $consumedMoney );
  }

  /** @test */
  public function order_can_count_consumed_money_when_charging_with_lvl2_charger()
  {
    $order    = $this -> set_charging_prices();
    $kilowatt = $order -> kilowatt;
    

    // 2019 year, 10 march 00:00:00
    $now1 = Carbon :: create(2019, 3, 10, 0, 0, 0);
    $order -> updateChargingStatus( OrderStatusEnum :: CHARGING );

    Carbon :: setTestNow( $now1 );
    $payment  = factory( Payment :: class ) -> create(
      [
        'order_id'      => $order -> id,
        'confirm_date'  => now(),
        'type'          => PaymentTypeEnum :: CUT,
        'price'         => 20,
      ]
    );

    // Case 1 
    $kilowatt -> update([ 'consumed' => 500, 'charging_power' => 1000 ]);
    $consumedMoney = $order -> countConsumedMoney();
    $this -> assertEquals( 47.5 , $consumedMoney );
    

    $kilowatt -> update([ 'consumed' => 5000 ]);
    $order    -> load( 'kilowatt' );

    $consumedMoney = $order -> countConsumedMoney();
    $this -> assertEquals( 475, $consumedMoney );
  }
  
  /** @test */
  public function order_can_count_penalty_fee()
  {
    $this       ->  artisan('db:seed --class=ConfigSeeder');
    $config     =   Config :: first();
    $config     ->  penalty_price_per_minute = 0.6;
    $config     ->  save();

    $startTime  = Carbon :: create(2020, 3, 10, 12, 46, 7);
    $onPenalty  = Carbon :: create(2020, 3, 10, 12, 50, 10);
    $onFinish   = Carbon :: create(2020, 3, 10, 13, 10, 10); // 0.6 * 20 = 12

    Carbon :: setTestNow( $startTime );
    
    $order      = $this -> set_charging_prices();
    $kilowatt   = $order -> kilowatt;

    factory( Payment :: class ) -> create(
      [
        'order_id'      => $order -> id,
        'type'          => PaymentTypeEnum :: CUT,
        'price'         => 20,
        'confirm_date'  => now(),
      ]
    );

    Carbon :: setTestNow( $onPenalty );
    $kilowatt -> update([ 'consumed' => 50 , 'charging_power' => 1000, ]);
    // 95 GEL / (50 / 1000) = 4.75

    $order    -> load( 'kilowatt' );
    $order    -> updateChargingStatus( OrderStatusEnum :: ON_FINE ); 

    Carbon :: setTestNow( $onFinish );

    $this -> assertEquals(
      $order -> countPenaltyFee(),
      12,
    );
  }
}