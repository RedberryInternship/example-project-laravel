<?php

namespace Tests\Unit\Orders;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\UserCard;
use App\Kilowatt;
use App\Payment;
use App\Order;
use App\User;

class Relationship extends TestCase
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

}