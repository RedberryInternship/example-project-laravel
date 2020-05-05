<?php

namespace Tests\Users;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Traits\Testing\User;
use App\Enums\OrderStatus;
use App\User as AppUser;
use Tests\TestCase;
use App\Order;

class UserModel extends TestCase
{
  use RefreshDatabase,
      User;

  private $user;

  protected function setUp(): void
  {
    parent :: setUp();
  
    $this -> createUserAndReturnToken();
    $this -> user = AppUser :: first();
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
  public function user_has_orders()
  {
    factory( Order :: class ) -> create([ 'user_id' => $this -> user -> id ]);
    factory( Order :: class ) -> create([ 'user_id' => $this -> user -> id ]);
    factory( Order :: class ) -> create([ 'user_id' => $this -> user -> id ]);

    $user = $this -> user;
    $user -> load('orders');

    $this -> assertCount( 3, $user -> orders );
  }

  /** @test */
  public function user_has_active_orders()
  {
    factory( Order :: class ) -> create([ 'user_id' => $this -> user -> id, 'charging_status' => OrderStatus :: FINISHED ]);
    factory( Order :: class ) -> create([ 'user_id' => $this -> user -> id, 'charging_status' => OrderStatus :: FINISHED ]);
    factory( Order :: class ) -> create([ 'user_id' => $this -> user -> id, 'charging_status' => OrderStatus :: FINISHED ]);
    factory( Order :: class ) -> create([ 'user_id' => $this -> user -> id, 'charging_status' => OrderStatus :: INITIATED ]);
    factory( Order :: class ) -> create([ 'user_id' => $this -> user -> id, 'charging_status' => OrderStatus :: CHARGING ]);
    
    $user = $this -> user;
    $user -> load('orders');
    $user -> load('active_orders');

    $this -> assertCount( 5, $user -> orders );
    $this -> assertCount( 2, $user -> active_orders );
  }
}