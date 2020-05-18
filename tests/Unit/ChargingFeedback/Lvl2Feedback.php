<?php

namespace Tests\Unit\ChargingFeedback;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Traits\Helper;
use Tests\TestCase;
use Carbon\Carbon;

use App\Enums\OrderStatus as OrderStatusEnum;
use App\Enums\PaymentType as PaymentTypeEnum;

use App\Payment;
use App\Config;
use App\Order;
use App\User;

class Lvl2Feedback extends TestCase
{
  use RefreshDatabase,
      Helper;

  private $uri;
  private $token;
  private $stop_url;
  private $update_url;
  private $order_url;

  protected function setUp(): void
  {
    parent::setUp();

    $this -> update_url = '/chargers/transactions/update/';
    $this -> stop_url   = '/chargers/transactions/finish/';
    $this -> token      = $this -> create_user_and_return_token();
    $this -> uri        = config( 'app' )[ 'uri' ];
    $this -> order_url  = $this -> uri . 'order/';
  }

  protected function tearDown(): void
  {
    $this -> beforeApplicationDestroyed( function (){
      foreach( DB :: getConnections() as $connection )
      {
        $connection -> disconnect();
      }
    });

    parent :: tearDown();
  }

  /** @test */
  public function update_order_adds_kilowatt_record()
  {  
    $this -> create_order_with_charger_id_of_29();
    
    $order = Order::first();
    
    $this -> get( $this -> update_url . $order -> charger_transaction_id . '/7' );
    $this -> get( $this -> update_url . $order -> charger_transaction_id . '/14000' );

    $kilowatts = $order -> kilowatt -> consumed;
    
    $this -> assertEquals( 14, $kilowatts );

    
    $this -> tear_down_order_data_with_charger_id_of_29();
  }

  /** @test */
  public function order_status_becomes_FINISHED_when_finished()
  {
    $this -> create_order_with_charger_id_of_29();
    
    $order = Order :: first();

    $this -> get( $this -> stop_url . $order -> charger_transaction_id );

    $updatedChargingStatus = Order :: first() -> charging_status;
    
    $this -> assertEquals( OrderStatusEnum :: FINISHED, $updatedChargingStatus );
    
    $this -> tear_down_order_data_with_charger_id_of_29();
  }

  /** @test */
  public function order_can_successfully_finish_with_penalty_fee()
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
    $order      -> updateChargingStatus( OrderStatusEnum :: CHARGING );
    $kilowatt   = $order -> kilowatt;

    // Paid 20 GEL
    factory( Payment :: class ) -> create(
      [
        'order_id'      => $order -> id,
        'type'          => PaymentTypeEnum :: CUT,
        'price'         => 20,
        'confirm_date'  => now(),
      ]
    );

    Carbon :: setTestNow( $onPenalty );
    $kilowatt -> update([ 'consumed' => 50, 'charging_power' => 1000, ]);
    // 95 GEL / (50 / 1000) = 4.75

    $order    -> load( 'kilowatt' );
    $order    -> updateChargingStatus( OrderStatusEnum :: ON_FINE ); 
    
    Carbon :: setTestNow( $onFinish );

    $this     -> get( $this -> stop_url . $order -> charger_transaction_id );

    $user     = User :: first();
    $response = $this -> actAs( $user ) -> get( $this -> order_url . $order -> id ) -> decodeResponseJson();
    
    $this -> assertEquals( 4.75,  $response [ 'consumed_money']);
    $this -> assertEquals( 20,    $response [ 'already_paid'  ]);
    $this -> assertEquals( 15.25, $response [ 'refund_money'  ]);
    $this -> assertEquals( 12,    $response [ 'penalty_fee'   ]);
  }

}