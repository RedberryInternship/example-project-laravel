<?php

namespace Tests\Unit\V2\Orders;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

use Tests\Unit\V1\Traits\Helper;
use Tests\TestCase;

use App\Enums\ConnectorType as ConnectorTypeEnum;
use App\Enums\PaymentType as PaymentTypeEnum;
use App\Enums\OrderStatus as OrderStatusEnum;


use App\ChargerConnectorType;
use App\ConnectorType;
use App\Payment;
use App\Config;
use App\Order;
use App\User;

class OrderWithPricing extends TestCase
{
  use RefreshDatabase,
      Helper;

  private $uri;
  private $user;
  private $order;

  protected function setUp(): void
  {
    parent::setUp();

    $this -> uri    = config( 'app' )[ 'uri' ];
    $this -> user   = factory( User :: class ) -> create();
    $this -> order  = factory( Order :: class ) -> create([ 'user_id' => $this -> user -> id ]);
  }

  /** @test */
  public function it_can_count_consumed_money_when_charging_with_lvl2_charger()
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
  public function it_can_count_penalty_fee()
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