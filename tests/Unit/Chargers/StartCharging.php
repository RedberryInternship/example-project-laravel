<?php

namespace Tests\Unit\Chargers;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

use App\ChargerConnectorType;
use App\ChargerTransaction;
use App\Kilowatt;

use App\Traits\Testing\Charger as ChargerTrait;
use App\Traits\Testing\User as UserTrait;

class StartCharging extends TestCase {
  
  use RefreshDatabase,
      UserTrait,
      ChargerTrait;

  private $token;
  private $uri;

  protected function setUp(): void
  {
    parent::setUp();

    $this -> token  = $this -> createUserAndReturnToken();
    $this -> uri    = config( 'app' )['uri'];
  }

  /** @test */  
  public function start_charging_has_charger_connector_type_id_error_when_not_providing_it()
  {
    $response = $this 
      -> withHeader( 'Authorization', 'Bearer ' . $this -> token )
      -> post( $this -> uri . 'charging/start' );
    
    $response -> assertJsonValidationErrors([ 'charger_connector_type_id' ]);
  }

  /** @test */
  public function start_charging_creates_new_charger_transaction_record_with_kilowatt()
  {
    $this -> initiate_charger_transaction_with_ID_of_29();
      
    $charger_transactions_count = ChargerTransaction::count();
    $kilowatt_count             = Kilowatt::count();

    $this -> assertTrue( $charger_transactions_count > 0 );
    $this -> assertTrue( $kilowatt_count > 0 );

    $this -> finish_charger_transaction_with_ID_of_29();
  }


  /** @test */
  public function start_charging_has_400_status_code_when_bad_request()
  {

    // not providing with [charger_connector_id] array
    $response = $this
      -> withHeader( 'Authorization','Bearer ' . $this -> token )
      -> post( $this -> uri . 'charging/start' );
    
    $response -> assertStatus( 400 );
  }


  /** @test */
  public function when_charger_transaction_is_initiated_status_is_INITIATED()
  {
    $this -> initiate_charger_transaction_with_ID_of_29();
    
    $charger_connector_type = ChargerConnectorType::first();

    $response = $this -> withHeader( 'Authorization', 'Bearer ' . $this -> token )
                      -> get( $this -> uri .'charging/status/' . $charger_connector_type -> id );

    $response = $response -> decodeResponseJson();

    $this -> assertEquals( 'INITIATED', $response['payload']['status']);

    $this -> finish_charger_transaction_with_ID_of_29();
  }

  /** @test */
  public function when_charger_is_not_free_dont_charge()
  {
    $this -> initiate_charger_transaction_with_ID_of_29();

    $response = $this -> withHeader( 'token', 'Bearer ' . $this -> token)
      -> post($this -> uri . 'charging/start', [
        'charger_connector_type_id' => ChargerConnectorType::first() -> id
      ]);

    $response = (object) $response -> decodeResponseJson();

    $this -> assertEquals( 'The Charger is not free.', $response -> message );

    $this -> finish_charger_transaction_with_ID_of_29();
  }
}
