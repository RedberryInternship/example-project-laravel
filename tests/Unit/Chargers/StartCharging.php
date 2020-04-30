<?php

namespace Tests\Unit\Chargers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

use App\Enums\OrderStatus;

use App\ChargerConnectorType;
use App\ConnectorType;
use App\Kilowatt;
use App\Charger;
use App\Enums\ChargingType;
use App\Order;

use App\Traits\Testing\Charger as ChargerTrait;
use App\Traits\Testing\User as UserTrait;
use App\Traits\Message;

use App\Facades\Simulator;

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

  protected function tearDown(): void
  {
    $this -> beforeApplicationDestroyed( function () {
      foreach(DB::getConnections() as $connection )
      {
        $connection -> disconnect();
      }
    });
    parent :: tearDown();
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
        'charging_type'             => ChargingType :: BY_AMOUNT,
      ]);

    $responseErrors = $response -> decodeResponseJson() [ 'errors' ][ 'charger_connector_type_id' ];
    $hasError       = in_array('Such charger connector type doesn\'t exists in db.', $responseErrors);
    
    $this -> assertTrue( $hasError );
  }

  /** @test */
  public function start_charging_has_charger_error_when_it_doesnt_exists()
  {

    factory( ChargerConnectorType::class ) -> create();
    DB :: table( 'chargers' ) -> delete();

    $response = $this 
      -> withHeader( 'Authorization', 'Bearer ' . $this -> token )
      -> post( $this -> url, [
        'charger_connector_type_id' => 1,
        'charging_type'             => ChargingType :: BY_AMOUNT,
        'price'                     => 50,
      ]);

    $responseErrors = $response -> decodeResponseJson() [ 'errors' ][ 'charger_connector_type_id' ];
    $hasError       = in_array( 'ChargerConnectorType doesn\'t have charger relation.', $responseErrors );
    
    $this -> assertTrue( $hasError );
  }

  /** @test */
  public function start_charging_doesnt_have_charger_error_when_it_exists()
  {

    Simulator :: plugOffCable( 29 );
    Simulator :: upAndRunning( 29 );
    sleep( 1 );

    $charger = factory( Charger::class ) -> create([ 'charger_id' => 29 ]);

    factory( ChargerConnectorType::class ) -> create([
      'charger_id' => $charger -> id
    ]);

    $response = $this 
      -> withHeader( 'Authorization', 'Bearer ' . $this -> token )
      -> post( $this -> url, [
        'charger_connector_type_id' => 1,
        'charging_type'             => ChargingType :: BY_AMOUNT,
        'price'                     => 50,
      ]);
        
    $response -> assertJsonMissingValidationErrors( 'charger_connector_type_id' );
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
        'charging_type'             => ChargingType :: FULL_CHARGE,
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
        in_array( 'Charging Type should be BY_AMOUNT or FULL_CHARGE.', $chargingTypeErrors )
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
      'charger_id'        => $charger -> id,
      'connector_type_id' => 1
    ]);

    $response = $this 
      -> withHeader( 'Authorization', 'Bearer ' . $this -> token )
      -> post( $this -> url, [
        'charger_connector_type_id' => 1,
        'charging_type'             => ChargingType :: BY_AMOUNT,
      ]);

      $response -> assertJsonValidationErrors('price');
  }

  /** @test */
  public function start_charging_has_no_price_error_when_charging_type_is_not_by_amount()
  {
    
    $charger = factory( Charger::class ) -> create();

    factory( ChargerConnectorType::class ) -> create([
      'charger_id'        => $charger -> id,
      'connector_type_id' => 1
    ]);

    $response = $this 
      -> withHeader( 'Authorization', 'Bearer ' . $this -> token )
      -> post( $this -> url, [
        'charger_connector_type_id' => 1,
        'charging_type'             => ChargingType :: FULL_CHARGE,
      ]);

      $response -> assertJsonMissingValidationErrors('price');
  }

  /** @test */
  public function charging_price_needs_to_be_numeric()
  {
    $charger = factory( Charger::class ) -> create();

    factory( ChargerConnectorType::class ) -> create([
      'charger_id'        => $charger -> id,
      'connector_type_id' => 1
    ]);
    
    $responseWithoutNumeric = $this 
      -> withHeader( 'Authorization', 'Bearer ' . $this -> token )
      -> post( $this -> url, [
        'charger_connector_type_id' => 1,
        'charging_type'             => ChargingType :: BY_AMOUNT,
        'price'                     => 'mas123',
      ]);

    
    $responseWithNumeric = $this 
      -> withHeader( 'Authorization', 'Bearer ' . $this -> token )
      -> post( $this -> url, [
        'charger_connector_type_id' => 1,
        'charging_type'             => ChargingType :: BY_AMOUNT,
        'price'                     => 2.777
      ]);
    
    $responseWithoutNumeric -> assertJsonValidationErrors('price');
    $responseWithNumeric -> assertJsonMissingValidationErrors('price');
  }

  /** @test */
  public function start_charging_creates_new_order_record_with_kilowatt()
  {
    $this -> create_order_with_charger_id_of_29();
      
    $orders_count   = Order::count();
    $kilowatt_count = Kilowatt::count();

    $this -> assertTrue( $orders_count > 0 );
    $this -> assertTrue( $kilowatt_count > 0 );

    $this -> tear_down_order_data_with_charger_id_of_29();
  }


  /** @test */
  public function start_charging_has_422_status_code_when_bad_request()
  {

    // not providing with [charger_connector_id] array
    $response = $this
      -> withHeader( 'Authorization','Bearer ' . $this -> token )
      -> post( $this -> uri . 'charging/start' );
    
    $response -> assertStatus( 422 );
  }


  /** @test */
  public function when_order_is_created_status_is_INITIATED()
  {
    $this -> create_order_with_charger_id_of_29();
    $charger_connector_type = ChargerConnectorType::first();

    $response = $this -> withHeader( 'Authorization', 'Bearer ' . $this -> token )
                      -> get( $this -> uri .'charging/status/' . $charger_connector_type -> id );

    $response = $response -> decodeResponseJson();

    $this -> assertEquals( OrderStatus :: INITIATED, $response['payload']['status']);

    $this -> tear_down_order_data_with_charger_id_of_29();
  }

  /** @test */
  public function when_charger_is_not_free_dont_charge()
  {
    $this -> create_order_with_charger_id_of_29();

    $response = $this -> withHeader( 'token', 'Bearer ' . $this -> token)
      -> post($this -> uri . 'charging/start', [
        'charger_connector_type_id' => ChargerConnectorType::first() -> id,
        'charging_type'             => ChargingType :: FULL_CHARGE,
      ]);

    $response = (object) $response -> decodeResponseJson();
    
    $this -> assertEquals( $this -> messages [ 'charger_is_not_free' ], $response -> message );

    $this -> tear_down_order_data_with_charger_id_of_29();
  }
}
