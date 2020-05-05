<?php

namespace Tests\Unit\ChargingFeedback;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

use App\Enums\OrderStatus;

use App\Order;

use App\Traits\Testing\Charger as ChargerTrait;
use App\Traits\Testing\User as UserTrait;

class Lvl2Feedback extends TestCase
{
  use RefreshDatabase,
      ChargerTrait,
      UserTrait;

  private $token;
  private $update_url;
  private $stop_url;

  protected function setUp(): void
  {
    parent::setUp();

    $this -> update_url = '/chargers/transactions/update/';
    $this -> stop_url   = '/chargers/transactions/finish/';
    $this -> token      = $this -> createUserAndReturnToken();
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
  

}