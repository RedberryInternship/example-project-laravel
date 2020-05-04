<?php

namespace Tests\Unit\Orders;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Carbon\Carbon;

use App\Enums\ConnectorType as ConnectorTypeEnum;
use App\Enums\PaymentType as PaymentTypeEnum;

use App\ChargerConnectorType;
use App\FastChargingPrice;
use App\ChargingPrice;
use App\ConnectorType;
use App\UserCard;
use App\Kilowatt;
use App\Payment;
use App\Order;
use App\User;
use Illuminate\Support\Facades\Schema;

class OrderModel extends TestCase
{
  use RefreshDatabase;

  private $user;
  private $order;

  protected function setUp(): void
  {
    parent::setUp();

    $this -> user   = factory( User :: class ) -> create();
    $this -> order  = factory( Order :: class ) -> create([ 'user_id' => $this -> user -> id ]);
  }

  /** @test */
  public function order_has_user()
  {
    $order = $this -> order -> load( 'user' );

    $this -> assertTrue( !! $order -> user );
  }

  /** @test */
  public function order_has_charging_type()
  {
    $order = $this -> order -> load( 'charging_type' );
    
    $this -> assertTrue( !! $order -> charging_type );
  }

  /** @test */
  public function order_has_payments()
  {

    $order = $this -> order;

    factory( Payment::class ) -> create([ 'order_id' => $order -> id ]);
    factory( Payment::class ) -> create([ 'order_id' => $order -> id ]);
    factory( Payment::class ) -> create([ 'order_id' => $order -> id ]);

    $order -> load('payments');

    $this -> assertCount( 3, $order -> payments );
  }

  /** @test */
  public function order_has_user_card()
  {
    $userCard = factory( UserCard :: class ) -> create();
    $order    = $this -> order;

    $order -> user_card_id = $userCard -> id;
    $order -> save();

    $this -> assertTrue( !! $order -> user_card );
  }

  /** @test */
  public function order_has_charger_connector_type()
  {
    $order = $this -> order -> load( 'charger_connector_type' );
    
    $this -> assertTrue( !! $order -> charger_connector_type );
  }

  /** @test */
  public function order_has_kilowatt()
  {
    $order = $this -> order;
    
    factory( Kilowatt :: class ) -> create([ 'order_id' => $order -> id ]);

    $order -> load( 'kilowatt' );

    $this -> assertTrue( !! $order -> kilowatt );
  }

  /** @test */
  public function order_can_create_and_add_kilowatt()
  {
    $order = $this -> order;

    $order -> createKilowatt( 7 );
    $order -> addKilowatt( 123 );
    $order -> addKilowatt( 192 );

    $this  -> assertCount( 3,  $order -> kilowatt -> consumed );
  }

  /** @test */
  public function order_can_get_all_consumed_kilowatt_collection()
  {
    $order = $this -> order;

    $order -> createKilowatt( 7 );
    $order -> addKilowatt( 123 );
    $order -> addKilowatt( 192 );

    $kilowattsCollection = $order -> consumedKilowatts();

    $this -> assertCount(3, $kilowattsCollection);
    $this -> assertTrue( $kilowattsCollection instanceOf Collection );
  }

  /** @test */
  public function order_can_get_last_consumed_kilowatt()
  {
    $order = $this -> order;

    $order -> addKilowatt( 7 );
    $order -> addKilowatt( 8 );
    $order -> addKilowatt( 9 );

    $latestConsumedKilowattValue = $order -> getLatestConsumedKilowatt() -> value;

    $this -> assertEquals( 9, $latestConsumedKilowattValue );
  }

  /** @test */
  public function order_can_get_only_confirmed_orders()
  {
    Order :: truncate();

    factory( Order :: class ) -> create([ 'confirmed' => true ]);   
    factory( Order :: class ) -> create([ 'confirmed' => true ]);   
    factory( Order :: class ) -> create([ 'confirmed' => true ]);   
    factory( Order :: class ) -> create([ 'confirmed' => false ]);  
    factory( Order :: class ) -> create([ 'confirmed' => false ]);

    $this -> assertCount(
      3,
      Order :: confirmed() -> get(),
    );
  }

  /** @test */
  public function order_can_get_only_confirmed_payments()
  {
    factory( Payment :: class ) -> create([ 'order_id' => $this -> order -> id, 'confirmed' => true ]);
    factory( Payment :: class ) -> create([ 'order_id' => $this -> order -> id, 'confirmed' => true ]);
    factory( Payment :: class ) -> create([ 'order_id' => $this -> order -> id, 'confirmed' => true ]);
    factory( Payment :: class ) -> create([ 'order_id' => $this -> order -> id, 'confirmed' => false ]);
    factory( Payment :: class ) -> create([ 'order_id' => $this -> order -> id, 'confirmed' => false ]);

    $order = Order :: confirmedPayments() -> find( $this -> order -> id );

    $this -> assertCount( 3, $order -> payments );
  }

  /** @test */
  public function order_can_get_only_confirmed_payments_with_user_cards()
  {
    $userCard = factory( UserCard :: class ) -> create([ 'user_id' => $this -> user -> id ]);

    factory( Payment :: class, 3 ) -> create(
      [ 
        'order_id'     => $this -> order -> id, 
        'confirmed'    => true,
        'user_card_id' => $userCard -> id
      ]
    );

    factory( Payment :: class, 2 ) -> create(
      [
        'order_id'     => $this -> order -> id,
        'confirmed'    => false,
        'user_card_id' => $userCard -> id,
      ]
    );

    $order = Order :: confirmedPaymentsWithUserCards() -> find( $this -> order -> id );
    
    $this -> assertCount( 3, $order -> payments );
    $this -> assertTrue( !! $order -> payments -> first() -> user_card );
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
    $connectorType        = ConnectorType :: whereName( ConnectorTypeEnum :: TYPE_2 ) -> first();
    $chargerConnectorType = factory( ChargerConnectorType :: class ) -> create(
      [
        'connector_type_id' => $connectorType -> id,
      ]
    );

    // Night till morning
    factory( ChargingPrice :: class ) -> create(
      [
        'charger_connector_type_id' => $chargerConnectorType -> id,
        'min_kwt'                   => 0,
        'max_kwt'                   => 5,
        'start_time'                => '00:00',
        'end_time'                  => '09:00',
        'price'                     => 5,
      ]
    );

    factory( ChargingPrice :: class ) -> create(
      [
        'charger_connector_type_id' => $chargerConnectorType -> id,
        'min_kwt'                   => 6,
        'max_kwt'                   => 20,
        'start_time'                => '00:00',
        'end_time'                  => '09:00',
        'price'                     => 25,
      ]
    );
    
    factory( ChargingPrice :: class ) -> create(
      [
        'charger_connector_type_id' => $chargerConnectorType -> id,
        'min_kwt'                   => 21,
        'max_kwt'                   => 10000000,
        'start_time'                => '00:00',
        'end_time'                  => '09:00',
        'price'                     => 40,
      ]
    );

    // Morning till night
    factory( ChargingPrice :: class ) -> create(
      [
        'charger_connector_type_id' => $chargerConnectorType -> id,
        'min_kwt'                   => 0,
        'max_kwt'                   => 5,
        'start_time'                => '09:01',
        'end_time'                  => '23:59',
        'price'                     => 50,
      ]
    );

    factory( ChargingPrice :: class ) -> create(
      [
        'charger_connector_type_id' => $chargerConnectorType -> id,
        'min_kwt'                   => 6,
        'max_kwt'                   => 20,
        'start_time'                => '09:01',
        'end_time'                  => '23:59',
        'price'                     => 70,
      ]
    );
    
    factory( ChargingPrice :: class ) -> create(
      [
        'charger_connector_type_id' => $chargerConnectorType -> id,
        'min_kwt'                   => 21,
        'max_kwt'                   => 10000000,
        'start_time'                => '09:01',
        'end_time'                  => '23:59',
        'price'                     => 95,
      ]
    );

    $order    = factory( Order :: class )   -> create(
      [
        'charger_connector_type_id' => $chargerConnectorType -> id,
      ]
    );

    $order -> createKilowatt( 0, 1000 );

    // 2019 year, 10 march 00:00:00
    $now1 = Carbon :: create(2019, 3, 10, 0, 0, 0);

    // 2019 year, 10 march 01:00:00
    $now2 = Carbon :: create(2019, 3, 10, 1, 0, 0);

    // 2019 year, 10 march 20:00:00
    $now3 = Carbon :: create(2019, 3, 10, 20, 00, 00);

    // 2019 year, 10 march 21:00:00
    $now3 = Carbon :: create(2019, 3, 10, 21, 00, 00);


    Carbon :: setTestNow( $now1 );
    $payment  = factory( Payment :: class ) -> create(
      [
        'order_id'                  => $order -> id,
        'confirm_date'              => now(),
      ]
    );

    // Case 1 
    $order -> addKilowatt( 500 );
    $consumedMoney = $order -> countConsumedMoney();
    $this -> assertEquals( 20.0, $consumedMoney );
    
    $order -> addKilowatt( 5000 );
    $consumedMoney = $order -> countConsumedMoney();
    $this -> assertEquals( 200.0, $consumedMoney );


    // Case 2
    $payment -> confirm_date = $now3;
    $payment -> save();
    
    $order -> addKilowatt( 500 );
    $consumedMoney = $order -> countConsumedMoney();
    $this -> assertEquals( 47.5, $consumedMoney );
  } 
}