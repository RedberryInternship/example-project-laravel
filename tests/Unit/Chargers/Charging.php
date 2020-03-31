<?php

namespace Tests\Unit\Chargers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Traits\Testing\User as UserTrait;

class Charging extends TestCase {
  
  use RefreshDatabase;
  use UserTrait;

  private $token;
  private $uri;

  protected function setUp(): void
  {
    parent::setUp();

    $this -> token = $this -> createUserAndReturnToken();
    $this -> uri = config('app')['uri'];
  }

  /** @test */  
  public function start_charging_has_charger_ID_error_when_not_providing_it()
  {


    $response = $this 
      -> withHeader('Authorization', 'Bearer '.$this -> token)
      -> post($this -> uri .'charging/start',[
      'connector_id' => 7,
    ]);
    
    $response -> assertJsonValidationErrors(['charger_id']);
  }


  /** @test */  
  public function start_charging_has_connector_ID_error_when_not_providing_it()
  {

    $response = $this 
      -> withHeader('Authorization', 'Bearer '.$this -> token)
      -> post($this -> uri .'charging/start',[
      'charger_id' => 7,
    ]);
    
    $response -> assertJsonValidationErrors(['connector_id']);
  }

  /** @test */
  public function start_charging_has_400_status_code_when_bad_request()
  {

    // not providing with [charger_id, connector_id] array
    $response = $this
      -> withHeader('Authorization','Bearer '.$this -> token)
      -> post($this -> uri .'charging/start');
    
    $response -> assertStatus(400);
  }


  /** @test */  
  public function stop_charging_has_charger_ID_error_when_not_providing_it()
  {
    $response = $this
      -> withHeader('Authorization','Bearer '.$this -> token)
      -> post($this -> uri .'charging/stop',[
      'transaction_id' => 7
    ]);
    
    $response -> assertJsonValidationErrors(['charger_id']);
  }

  /** @test */
  public function stop_charging_has_transaction_ID_error_when_not_providing_it()
  {
    $response = $this 
      -> withHeader('Authorization','Bearer '.$this -> token)
      -> post($this -> uri .'charging/stop',['charger_id', 7]);

    $response -> assertJsonValidationErrors(['transaction_id']);
  }

  // not providing with [charger_id, connector_id] array
  public function stop_charging_has_400_status_code_when_bad_request()
  {
    $response = $this
      -> withHeader('Authorization', 'Bearer '. $this -> token)
      -> post('charging/stop');

    $response -> assertStatus(400);
  }
}


/*

    $response->dump();  => Dumps response body
    $response->dumpHeaders(); => Dumps Headers
    $this -> withoutExceptionHandling();
    $response->decodeResponseJson()
*/
