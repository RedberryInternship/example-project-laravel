<?php

namespace Tests\Unit\Chargers;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

use App\ChargerConnectorType;
use App\ChargerTransaction;
use App\ConnectorType;
use App\Kilowatt;
use App\Charger;

use App\Traits\Testing\Charger as ChargerTrait;
use App\Traits\Testing\User as UserTrait;
use App\Traits\Message;

class StartCharging extends TestCase {
  
  use RefreshDatabase,
      UserTrait,
      ChargerTrait,
      Message;

  private $token;
  private $uri;
  private $url;

  protected function setUp(): void
  {
    parent::setUp();

    $this -> token  = $this -> createUserAndReturnToken();
    $this -> uri    = config( 'app' )['uri'];
    $this -> url    = $this -> uri . 'charging/start';
  }

  /** @test */  
  public function start_charging_has_charger_connector_type_id_error_when_not_providing_it()
  {
    $response = $this 
      -> withHeader( 'Authorization', 'Bearer ' . $this -> token )
      -> post( $this -> url );
    
    $response -> assertJsonValidationErrors([ 'charger_connector_type_id' ]);
  }

  /** @test */
  public function start_charging_has_charger_connector_type_error_when_it_is_doesnt_exist()
  {

    $response = $this 
      -> withHeader( 'Authorization', 'Bearer ' . $this -> token )
      -> post( $this -> url, [
        'charger_connector_type_id' => 1,
        'charging_type'             => 'BY-AMOUNT',
      ]);

    $responseErrors = $response -> decodeResponseJson() [ 'errors' ][ 'charger_connector_type_id' ];
    $hasError       = in_array('Such charger connector type doesn\'t exists in db.', $responseErrors);
    
    $this -> assertTrue( $hasError );
  }

  /** @test */
  public function start_charging_has_charger_error_when_it_doesnt_exists()
  {

    factory( ChargerConnectorType::class ) -> create();

    $response = $this 
      -> withHeader( 'Authorization', 'Bearer ' . $this -> token )
      -> post( $this -> url, [
        'charger_connector_type_id' => 1,
        'charging_type'             => 'BY-AMOUNT',
      ]);

    $responseErrors = $response -> decodeResponseJson() [ 'errors' ][ 'charger_connector_type_id' ];
    $hasError       = in_array( 'ChargerConnectorType doesn\'t have charger relation.', $responseErrors );
    
    $this -> assertTrue( $hasError );
  }

  /** @test */
  public function start_charging_doesnt_have_charger_error_when_it_exists()
  {
    $charger = factory( Charger::class ) -> create();

    factory( ChargerConnectorType::class ) -> create([
      'charger_id' => $charger -> id
    ]);

    $response = $this 
      -> withHeader( 'Authorization', 'Bearer ' . $this -> token )
      -> post( $this -> url, [
        'charger_connector_type_id' => 1,
        'charging_type'             => 'BY-AMOUNT',
      ]);

    $responseErrors = $response -> decodeResponseJson() [ 'errors' ][ 'charger_connector_type_id' ];
    $hasError       = ! in_array( 'ChargerConnectorType doesn\'t have charger relation.', $responseErrors );
    
    $this -> assertTrue( $hasError );
  }

  /** @test */
  public function start_charging_has_connector_type_error_when_it_doesnt_exists()
  {

    ConnectorType::truncate();
    
    $charger = factory( Charger::class ) -> create();

    $charger_connector_type = factory( ChargerConnectorType::class ) -> create([
      'charger_id' => $charger -> id
    ]);

    $response = $this 
      -> withHeader( 'Authorization', 'Bearer ' . $this -> token )
      -> post( $this -> url, [
        'charger_connector_type_id' => $charger_connector_type -> id,
        'charging_type'             => 'FULL-CHARGE',
      ]);
          
      $responseErrors = $response -> decodeResponseJson() [ 'errors' ][ 'charger_connector_type_id' ];
      $hasError       = in_array( 'ChargerConnectorType doesn\'t have connector_type relation.', $responseErrors );
      
      $this -> assertTrue( $hasError );
      
  }

  /** @test */
  public function start_charging_has_charging_type_error_when_not_providing_it()
  {
    $response = $this
      -> withHeader( 'Authorization', 'Bearer' . $this -> token )
      -> post( $this -> url, [
        'charger_connector_type_id' => 7
      ]);
    
    $response -> assertJsonValidationErrors( 'charging_type' );
  }


  /** @test */
  public function start_charging_has_charging_type_errors_when_incorrect_attribute()
  {
    $response = $this
      -> withHeader( 'Authorization', 'Bearer' . $this -> token )
      -> post( $this -> url, [
        'charger_connector_type_id' => 7,
        'charging_type' => 1512,
      ]);
      
      $response = $response -> decodeResponseJson();
    
      $chargingTypeErrors = $response[ 'errors' ][ 'charging_type' ];

      $this -> assertTrue(
        in_array( 'Charging Type should be BY-AMOUNT or FULL-CHARGE.', $chargingTypeErrors )
      );

      $this -> assertTrue(
        in_array( 'Charging Type should be string.', $chargingTypeErrors )
      );
  }

  /** @test */
  public function start_charging_has_price_error_when_charging_type_is_by_amount()
  {
    
    $charger = factory( Charger::class ) -> create();

    factory( ChargerConnectorType::class ) -> create([
      'charger_id' => $charger -> id,
      'connector_type_id' => 1
    ]);

    $response = $this 
      -> withHeader( 'Authorization', 'Bearer ' . $this -> token )
      -> post( $this -> url, [
        'charger_connector_type_id' => 1,
        'charging_type'             => 'BY-AMOUNT',
      ]);

      $response -> assertJsonValidationErrors('price');
  }

  /** @test */
  public function start_charging_has_no_price_error_when_charging_type_is_not_by_amount()
  {
    
    $charger = factory( Charger::class ) -> create();

    factory( ChargerConnectorType::class ) -> create([
      'charger_id' => $charger -> id,
      'connector_type_id' => 1
    ]);

    $response = $this 
      -> withHeader( 'Authorization', 'Bearer ' . $this -> token )
      -> post( $this -> url, [
        'charger_connector_type_id' => 1,
        'charging_type'             => 'FULL-CHARGE',
      ]);

      $response -> assertJsonMissingValidationErrors('price');
  }

  /** @test */
  public function charging_price_needs_to_be_numeric()
  {
    $charger = factory( Charger::class ) -> create();

    factory( ChargerConnectorType::class ) -> create([
      'charger_id' => $charger -> id,
      'connector_type_id' => 1
    ]);
    
    $responseWithoutNumeric = $this 
      -> withHeader( 'Authorization', 'Bearer ' . $this -> token )
      -> post( $this -> url, [
        'charger_connector_type_id' => 1,
        'charging_type'             => 'BY-AMOUNT',
        'price'                     => 'mas123',
      ]);

    
    $responseWithNumeric = $this 
      -> withHeader( 'Authorization', 'Bearer ' . $this -> token )
      -> post( $this -> url, [
        'charger_connector_type_id' => 1,
        'charging_type'             => 'BY-AMOUNT',
        'price'                     => 2.777
      ]);
    
    $responseWithoutNumeric -> assertJsonValidationErrors('price');
    $responseWithNumeric -> assertJsonMissingValidationErrors('price');
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
    ChargerConnectorType :: truncate();
    dd(
      Charger :: all() -> toArray()
    );
    $this -> initiate_charger_transaction_with_ID_of_29();
    dd(
      Charger              :: all() -> toArray(),
      ConnectorType        :: all() -> toArray(),
      ChargerConnectorType :: all() -> toArray(),
    );
    $charger_connector_type = ChargerConnectorType::first();

    $response = $this -> withHeader( 'Authorization', 'Bearer ' . $this -> token )
                      -> get( $this -> uri .'charging/status/' . $charger_connector_type -> id );

    $response = $response -> decodeResponseJson();
    dd($response);
    $this -> assertEquals( 'INITIATED', $response['payload']['status']);

    $this -> finish_charger_transaction_with_ID_of_29();
  }

  /** @test */
  public function when_charger_is_not_free_dont_charge()
  {
 
    $this -> initiate_charger_transaction_with_ID_of_29();

    $response = $this -> withHeader( 'token', 'Bearer ' . $this -> token)
      -> post($this -> uri . 'charging/start', [
        'charger_connector_type_id' => ChargerConnectorType::first() -> id,
        'charging_type'             => 'FULL-CHARGE'
      ]);

    $response = (object) $response -> decodeResponseJson();

    $this -> assertEquals( $this -> messages [ 'charger_is_not_free' ], $response -> message );

    $this -> finish_charger_transaction_with_ID_of_29();
  }
}
