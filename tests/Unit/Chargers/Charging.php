<?php

namespace Tests\Unit\Chargers;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

use App\Facades\Simulator;
use App\Charger;
use App\ChargerTransaction;
use App\Facades\MockSyncer;
use App\Kilowatt;
use App\ChargerConnectorType;
use App\Traits\Testing\User as UserTrait;

class Charging extends TestCase {
  
  use RefreshDatabase;
  use UserTrait;

  private $token;
  private $uri;

  protected function setUp(): void
  {
    parent::setUp();

    $this -> token  = $this -> createUserAndReturnToken();
    $this -> uri    = config('app')['uri'];
  }

  /** @test */  
  public function start_charging_has_charger_connector_type_id_error_when_not_providing_it()
  {
    $response = $this 
      -> withHeader('Authorization', 'Bearer '.$this -> token)
      -> post($this -> uri .'charging/start');
    
    $response -> assertJsonValidationErrors(['charger_connector_type_id']);
  }

  /** @test */
  public function start_charging_creates_new_charger_transaction_record_with_kilowatt()
  {
    $this -> initiateChargerTransactionWithIdOf_29();
      
    $charger_transactions_count = ChargerTransaction::count();
    $kilowatt_count             = Kilowatt::count();

    $this -> assertTrue($charger_transactions_count > 0);
    $this -> assertTrue($kilowatt_count > 0);
  }

  /** @test */
  public function update_charger_transaction_adds_kilowatt_record()
  {  
    $this -> initiateChargerTransactionWithIdOf_29();
    
    $charger_transaction = ChargerTransaction::first();
    
    $this -> get('/chargers/transactions/update/'.$charger_transaction -> transactionID.'/7');
    $this -> get('/chargers/transactions/update/'.$charger_transaction -> transactionID.'/14');

    $kilowatts = $charger_transaction -> kilowatt -> consumed;
    
    $this -> assertCount(3, $kilowatts);
  }

  /** @test */
  public function charger_transaction_status_becomes_FINISHED_when_finished()
  {
    $this -> initiateChargerTransactionWithIdOf_29();
    
    $charger_transaction = ChargerTransaction::first();

    $this -> get('/chargers/transactions/finish/'. $charger_transaction -> transactionID);

    $new_charger_transaction_status = ChargerTransaction::first() -> status;
    
    $this -> assertEquals('FINISHED', $new_charger_transaction_status);
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
  public function stop_charging_has_charger_connector_type_id_error_when_not_providing_it()
  {
    $response = $this
      -> withHeader('Authorization','Bearer '.$this -> token)
      -> post($this -> uri .'charging/stop',[
      'transaction_id' => 7
    ]);
    
    $response -> assertJsonValidationErrors(['charger_connector_type_id']);
  }


  /** @test */
  public function stop_charging_sends_stop_charging_call_and_updates_db()
  {
    $this -> initiateChargerTransactionWithIdOf_29();

    $charger_connector_type = ChargerConnectorType::first();

    $this -> withHeader('Authorization', 'Bearer '.$this -> token)
      -> post($this -> uri . 'charging/stop', [
        'charger_connector_type_id' => $charger_connector_type -> id,
      ]);
    
    $charger_transaction = $charger_connector_type -> charger_transaction_first();

    $this -> assertEquals("CHARGED", $charger_transaction -> status);
  }

  /** @test */
  public function when_charger_transaction_is_initiated_status_is_INITIATED()
  {
    $this -> initiateChargerTransactionWithIdOf_29();
    
    $charger_connector_type = DB::table('charger_connector_types') -> first();

    $response = $this -> withHeader('Authorization', 'Bearer '. $this -> token)
                      -> get($this -> uri .'charging/status/'. $charger_connector_type -> id);

    $response = $response -> decodeResponseJson();

    $this -> assertEquals('INITIATED', $response['payload']['status']);
  }

  /** @test */
  public function when_charger_is_not_free_dont_charge()
  {
    $this -> initiateChargerTransactionWithIdOf_29();

    $response = $this -> withHeader('token', 'Bearer ' . $this -> token)
      -> post($this -> uri . 'charging/start', [
        'charger_connector_type_id' => ChargerConnectorType::first() -> id
      ]);

    $response = (object) $response -> decodeResponseJson();

    $this -> assertEquals('The Charger is not free.', $response -> message);
  }




  /*************** Helpers *****************/ 

  private function initiateChargerTransactionWithIdOf_29()
  {
    /** Charger of charger_id 29 is always free */
    Simulator::plugOffCable(29);
    for($i=0; $i < 10000000; $i+= 0.04); // Wait to perfectly finish disconnecting

    $new_charger        = MockSyncer::generateSingleMockCharger();
    $new_charger_id     = 29;
    $new_charger -> id  = $new_charger_id;

    MockSyncer::insertOrUpdateOne($new_charger);

    $charger_connector_type_id = Charger::with('connector_types')
      -> where('charger_id', $new_charger_id)
      -> first()
      -> connector_types
      -> first()
      -> pivot
      -> id;
      
    $this -> withHeader('Authorization', 'Bearer '.$this -> token)
      -> post($this -> uri .'charging/start', [
        'charger_connector_type_id' => $charger_connector_type_id ,
      ]);
  }
}
