<?php

namespace Tests\Unit\ChargingApi;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Traits\Helper;
use Tests\TestCase;

use App\Enums\OrderStatus;

use App\ChargerConnectorType;
use App\Order;

class Charging extends TestCase {
  
  use RefreshDatabase,
      Helper;

  private $token;
  private $uri;

  protected function setUp(): void
  {
    parent::setUp();

    $this -> token  = $this -> create_user_and_return_token();
    $this -> uri    = config( 'app' )[ 'uri' ];
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
  public function stop_charging_sends_stop_charging_call_and_updates_db()
  {
    $this -> create_order_with_charger_id_of_29();

    $order = Order :: first();

    $this -> withHeader( 'Authorization', 'Bearer ' . $this -> token )
      -> post($this -> uri . 'charging/stop', [
        'order_id' => $order -> id,
      ]);
    
    $order -> refresh();
    
    $this -> assertEquals( OrderStatus :: CHARGED, $order -> charging_status );
    
    $this -> tear_down_order_data_with_charger_id_of_29();
  }
}
