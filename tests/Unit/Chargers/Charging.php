<?php

namespace Tests\Unit\Chargers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\User;

use App\Traits\Testing\User as UserTrait;

class Charging extends TestCase {
  
  use RefreshDatabase;
  use UserTrait;

  private $token;

  protected function setUp(): void
  {
    parent::setUp();

    $this -> token = $this -> createUserAndReturnToken();
    
  }

  /** @test */  
  public function start_charging_has_charger_ID_error_when_not_providing_it()
  {

    $response = $this -> withHeaders([
      'Authorization' => 'Bearer '.$this -> token,
    ])-> post('/api/app/V1/charging/start',[
      'connector_id' => 7,
    ]);
    
    $response -> assertJsonValidationErrors(['charger_id']);
  }


  /** @test */  
  public function start_charging_has_connector_ID_error_when_not_providing_it()
  {

    $response = $this -> withHeaders([
      'Authorization' => 'Bearer '.$this -> token,
    ])-> post('/api/app/V1/charging/start',[
      'charger_id' => 7,
    ]);
    
    $response -> assertJsonValidationErrors(['connector_id']);
  }
  
}


/*

    $response->dump();  => Dumps response body
    $response->dumpHeaders(); => Dumps Headers
    $this -> withoutExceptionHandling();
    $response->decodeResponseJson()
*/
