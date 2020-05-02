<?php

namespace Tests\Unit\Orders;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Enums\OrderStatus;
use App\Enums\PaymentType;
use Tests\TestCase;
use App\Order;
use App\Payment;
use App\User;

use App\Traits\Testing\User as UserTrait;

class ActiveOrders extends TestCase
{
  use RefreshDatabase,
      UserTrait;

  
  private $user;
  private $token;
  private $uri;
  private $url;
  
  protected function setUp(): void
  {
    parent :: setUp();

    $this -> token    = $this -> createUserAndReturnToken();
    $this -> user     = User :: first();
    $this -> uri      = config()[ 'app.uri' ];
    $this -> url      = $this -> uri . 'active-orders';
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
    $response = $this -> request -> post( $this -> url );

    $response -> assertOk();
  }

  /** @test */
  public function it_returns_active_orders()
  {
    $user = $this -> user;
    factory( Order :: class )     -> create(
      [ 
        'user_id' => $user -> id, 
        'charging_status' => OrderStatus :: INITIATED,
      ]
    );

    factory( Order :: class )     -> create(
      [ 
        'user_id' => $user -> id, 
        'charging_status' => OrderStatus :: CHARGING, 
      ]
    );

    factory( Order :: class, 3 )  -> create(
      [ 
        'user_id' => $user -> id, 
        'charging_status' => OrderStatus :: FINISHED, 
      ]
    );

    $response = $this -> request -> post( $this -> url );
    $response = $response -> decodeResponseJson();
    
    $this -> assertCount( 2, $response );
  }

  /** @test */
  public function it_returns_correct_paid_money()
  {
    $order = factory( Order :: class ) -> create(
      [ 
        'user_id'         => $this -> user -> id, 
        'charging_status' => OrderStatus :: CHARGING, 
      ]
    );

    factory( Payment :: class ) -> create(
      [
        'order_id'        => $order -> id,
        'type'            => PaymentType :: CUT,
        'price'           => '27.81',
      ]
    );

    factory( Payment :: class ) -> create(
      [
        'order_id'        => $order -> id,
        'type'            => PaymentType :: CUT,
        'price'           => '2.2',
      ]
    );
    
    factory( Payment :: class ) -> create(
      [
        'order_id'        => $order -> id,
        'type'            => PaymentType :: FINE,
        'price'           => '10',
      ]
    );

    $response = $this     -> request -> post( $this -> url );
    $response = $response -> decodeResponseJson();
    
    $this -> assertEquals( 30.01, $response[ 0 ][ 'already_paid' ]);
  }
}