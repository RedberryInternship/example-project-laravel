<?php

namespace Tests\Unit\Chargers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

use App\ChargerConnectorType;

use App\Traits\Testing\Charger as ChargerTrait;
use App\Traits\Testing\User as UserTrait;

class Charging extends TestCase {
  
  use RefreshDatabase,
      UserTrait,
      ChargerTrait;

  private $token;
  private $uri;

  protected function setUp(): void
  {
    parent::setUp();

    $this -> token  = $this -> createUserAndReturnToken();
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
  public function stop_charging_has_charger_connector_type_id_error_when_not_providing_it()
  {
    $response = $this
      -> withHeader( 'Authorization', 'Bearer ' . $this -> token )
      -> post( $this -> uri . 'charging/stop', [
      'transaction_id' => 7
    ]);
    
    $response -> assertJsonValidationErrors([ 'charger_connector_type_id' ]);
  }


  /** @test */
  public function stop_charging_sends_stop_charging_call_and_updates_db()
  {
    $this -> create_order_with_charger_id_of_29();

    $chargerConnectorType = ChargerConnectorType::first();
  
    $this -> withHeader( 'Authorization', 'Bearer ' . $this -> token )
      -> post($this -> uri . 'charging/stop', [
        'charger_connector_type_id' => $chargerConnectorType -> id,
      ]);
    
    $chargerConnectorType -> load ( 'orders' );
    
    $order = $chargerConnectorType -> orders -> first();

    $this -> assertEquals( "CHARGED", $order -> charging_status );
    
    $this -> tear_down_order_data_with_charger_id_of_29();
  }
}
