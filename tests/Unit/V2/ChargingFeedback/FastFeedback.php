<?php

namespace Tests\Unit\V2\ChargingFeedback;

use Tests\TestCase;
use Tests\Unit\V2\Stubs\Order as OStub;
use App\Enums\OrderStatus as OrderStatusEnum;

use App\User;
use Carbon\Carbon;

class FastFeedback extends TestCase
{
  private $uri;
  private $user;
  private $order;
  private $stop_url;
  private $update_url;
  private $single_order_url;
  
  protected function setUp(): void
  {
    parent :: setUp();
    $this  -> artisan( 'migrate:fresh' );

    $this -> uri              = config( 'app' )[ 'uri' ];
    $this -> user             = factory( User :: class ) -> create();
    $this -> order            = OStub :: makeOrder( $this -> user -> id );
    $this -> stop_url         = '/chargers/transactions/finish/';
    $this -> update_url       = '/chargers/transactions/update/';
    $this -> single_order_url = $this -> uri . 'order/';
  }

  /** @test */
  public function it_does_nothing_when_doesnt_have_to_charge()
  {
    $now    =   now() -> addMinutes( 2 );
    Carbon  ::  setTestNow( $now );

    $response = $this 
      -> actAs( $this -> user )
      -> get(   $this -> single_order_url . $this -> order -> id );
    
    $response = ( object ) $response -> decodeResponseJson();

    $this -> assertEquals( 20, $response -> already_paid );
  }

  /** @test */
  public function it_cuts_20_when_consumed_money_is_more_then_paid()
  {
    $now    =   now() -> addMinutes( 16 );
    Carbon  ::  setTestNow( $now );

    $this -> get( $this -> update_url . $this -> order -> charger_transaction_id . '/' . 100000 );

    $response = $this 
      -> actAs( $this -> user )
      -> get(   $this -> single_order_url . $this -> order -> id );
    
    $response = ( object ) $response -> decodeResponseJson();

   $this -> assertEquals( 40, $response -> already_paid );
  }

  /** @test */
  public function it_ends_charging_when_consumed_money_is_more_than_paid_while_charging_by_amount()
  {
    $this -> order = OStub :: makeOrder( $this -> user -> id, true, true );
    $this -> order -> update([ 'target_price' => 22 ]);

    $now    =   now() -> addMinutes( 12 );
    Carbon  :: setTestNow( $now );
    
    $this -> get( $this -> update_url . $this -> order -> charger_transaction_id . '/' . 100000 );
    $this -> get( $this -> stop_url   . $this -> order -> charger_transaction_id                );
    $this -> order -> refresh();

    $this -> assertEquals( OrderStatusEnum :: FINISHED, $this -> order -> charging_status );
  }

  /** @test */
  public function it_stops_transaction_when_cable_is_plugged_of()
  {
    $now    =   now() -> addMinutes( 12 );
    Carbon  :: setTestNow( $now );
    
    $this -> get( $this -> update_url . $this -> order -> charger_transaction_id . '/' . 100000 );
    $this -> get( $this -> stop_url   . $this -> order -> charger_transaction_id                );
  
    $this -> order -> refresh();

    $this -> assertEquals( OrderStatusEnum :: FINISHED, $this -> order -> charging_status );
  }

  /** @test */
  public function it_makes_last_payments_when_cable_is_plugged_of()
  {
    $now    =   now() -> addMinutes( 22 );
    Carbon  :: setTestNow( $now );
    
    $this -> get( $this -> update_url . $this -> order -> charger_transaction_id . '/' . 100000 );
    $this -> get( $this -> stop_url   . $this -> order -> charger_transaction_id                );
    
    $response = ( object ) $this 
      -> actAs( $this -> user )
      -> get(   $this -> single_order_url . $this -> order -> id )
      -> decodeResponseJson();

    $this -> assertEquals( 0  , $response -> refund_money );
    $this -> assertEquals( 40 , $response -> already_paid );
  }
}