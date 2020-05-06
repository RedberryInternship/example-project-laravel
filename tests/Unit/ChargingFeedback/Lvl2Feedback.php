<?php

namespace Tests\Unit\ChargingFeedback;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Traits\Helper;
use Tests\TestCase;

use App\Enums\OrderStatus;

use App\Order;
use App\User;

class Lvl2Feedback extends TestCase
{
  use RefreshDatabase,
      Helper;

  private $token;
  private $update_url;
  private $stop_url;

  protected function setUp(): void
  {
    parent::setUp();

    $this -> update_url = '/chargers/transactions/update/';
    $this -> stop_url   = '/chargers/transactions/finish/';
    $this -> token      = $this -> create_user_and_return_token();
    $this -> uri        = config( 'app' )[ 'uri' ];
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
    $this -> get( $this -> update_url . $order -> charger_transaction_id . '/14' );

    $kilowatts = $order -> kilowatt -> consumed;
    
    $this -> assertCount( 3, $kilowatts );

    
    $this -> tear_down_order_data_with_charger_id_of_29();
  }

  /** @test */
  public function order_status_becomes_FINISHED_when_finished()
  {
    $this -> create_order_with_charger_id_of_29();
    
    $order = Order :: first();

    $this -> get( $this -> stop_url . $order -> charger_transaction_id );

    $updatedChargingStatus = Order :: first() -> charging_status;
    
    $this -> assertEquals( OrderStatus :: FINISHED, $updatedChargingStatus );
    
    $this -> tear_down_order_data_with_charger_id_of_29();
  }


  public function it_returns_updated_currency_fields_after_switched_into_charging_mode()
  {
    dump("Long Test!");
    $user   = User :: first();
    $this -> create_order_with_charger_id_of_29( $user -> id );

    $order  = Order :: first();

    $this -> get( $this -> update_url . $order -> charger_transaction_id . '/' . 10 );

    sleep(160);

    $this -> get( $this -> update_url . $order -> charger_transaction_id . '/' . 1000 );
    sleep(3);

    $response = $this -> withHeader( 'Authorization', 'Bearer ' . $this -> token) 
      -> get( $this -> uri . 'active-orders' );

    dump(
      $response ->decodeResponseJson(),
    );

    $order -> refresh();
    $order -> load( 'kilowatt' );

    dump(
      $order -> toArray(), 
    );
  }

}