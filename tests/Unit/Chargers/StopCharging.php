<?php

namespace Tests\Unit\Chargers;

use Illuminate\Foundation\Testing\RefreshDatabase;

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
    $this -> initiate_charger_transaction_with_ID_of_29();

    $charger_connector_type = ChargerConnectorType::first();

    $this -> withHeader( 'Authorization', 'Bearer ' . $this -> token )
      -> post($this -> uri . 'charging/stop', [
        'charger_connector_type_id' => $charger_connector_type -> id,
      ]);
    
    $charger_transaction = $charger_connector_type -> charger_transaction_first();

    $this -> assertEquals( "CHARGED", $charger_transaction -> status );
    
    $this -> finish_charger_transaction_with_ID_of_29();
  }
}
