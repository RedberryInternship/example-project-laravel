<?php

namespace Tests\Unit\V2\ChargingApi;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Unit\V2\Stubs\Order as OStub;
use Tests\TestCase;


use App\Enums\OrderStatus;

use App\Order;
use App\User;

class Charging extends TestCase {
  use RefreshDatabase;

  private $uri;
  private $user;

  protected function setUp(): void
  {
    parent::setUp();

    $this -> uri    = config( 'app' )[ 'uri' ];
    $this -> url    = $this -> uri . 'charging/stop';
    $this -> user   = factory( User :: class ) -> create();
  }

  /** @test */
  public function it_sends_stop_charging_call_and_updates_db()
  {
    $order = OStub :: makeOrder( $this -> user -> id, false );
    $user  = $this -> user;

    $this -> actAs( $user )
          -> post( $this -> url, 
            [
              'order_id' => $order -> id,
            ]
          );
    
    $order -> refresh();
    
    $this -> assertEquals( OrderStatus :: CHARGED, $order -> charging_status );    
  }
}
