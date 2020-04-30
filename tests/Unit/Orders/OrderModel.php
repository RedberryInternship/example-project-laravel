<?php

namespace Tests\Unit\Orders;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

use App\UserCard;
use App\Kilowatt;
use App\Payment;
use App\Order;
use App\User;

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
}