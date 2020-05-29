<?php

namespace Tests\Unit\V2\Validators;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

use Tests\TestCase;

use Tests\Unit\V2\Stubs\ChargerConnectorType as CCTStub;
use App\Enums\ConnectorType as ConnectorTypeEnum;
use App\Enums\ChargingType as ChargingTypeEnum;
use App\Enums\ChargerType as ChargerTypeEnum;

use App\ChargerConnectorType;
use App\ConnectorType;
use App\UserCard;
use App\Charger;
use App\Order;
use App\User;

use App\Traits\Message;

use App\Facades\Simulator;
use App\Facades\Charger as RealCharger;

class StartCharging extends TestCase {
  
  use RefreshDatabase,
      Message;

  private $user;
  private $uri;
  private $url;

  protected function setUp(): void
  {
    parent::setUp();

    $this -> user   = factory( User :: class ) -> create();
    $this -> uri    = config( 'app' )[ 'uri' ];
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
  public function it_has_charger_connector_type_id_error_when_not_providing_it()
  {
    $this -> makeChargerFree();

    $response = $this 
      -> actAs( $this -> user )
      -> post(  $this -> url );
    
    $response -> assertJsonValidationErrors([ 'charger_connector_type_id' ]);
  }

  /** @test */
  public function it_has_charger_connector_type_error_when_it_is_doesnt_exist()
  {
    $this -> makeChargerFree();

    $response = $this 
      -> actAs( $this -> user )
      -> post(  $this -> url, [
        'charger_connector_type_id' => 1,
        'charging_type'             => ChargingTypeEnum :: BY_AMOUNT,
      ]);

    $responseErrors = $response -> decodeResponseJson() [ 'errors' ][ 'charger_connector_type_id' ];
    $hasError       = in_array('Such charger connector type doesn\'t exists in db.', $responseErrors);
    
    $this -> assertTrue( $hasError );
  }

  /** @test */
  public function it_has_charger_error_when_it_doesnt_exists()
  {
    $this -> makeChargerFree();

    factory( ChargerConnectorType::class ) -> create();
    DB :: table( 'chargers' ) -> delete();

    $response = $this 
      -> actAs( $this -> user )
      -> post(  $this -> url, [
        'charger_connector_type_id' => 1,
        'charging_type'             => ChargingTypeEnum :: BY_AMOUNT,
        'price'                     => 50,
      ]);

    $responseErrors = $response -> decodeResponseJson() [ 'errors' ][ 'charger_connector_type_id' ];
    $hasError       = in_array( 'ChargerConnectorType doesn\'t have charger relation.', $responseErrors );
    
    $this -> assertTrue( $hasError );
  }

  /** @test */
  public function it_doesnt_have_charger_error_when_it_exists()
  {
    $this -> makeChargerFree();

    $charger = factory( Charger::class ) -> create([ 'charger_id' => 29 ]);

    factory( ChargerConnectorType::class ) -> create([
      'charger_id' => $charger -> id
    ]);

    $response = $this 
      -> actAs( $this -> user )
      -> post( $this -> url, [
        'charger_connector_type_id' => 1,
        'charging_type'             => ChargingTypeEnum :: BY_AMOUNT,
        'price'                     => 50,
      ]);
        
    $response -> assertJsonMissingValidationErrors( 'charger_connector_type_id' );
  }

  /** @test */
  public function it_has_connector_type_error_when_it_doesnt_exists()
  {
    $this -> makeChargerFree();

    ConnectorType::truncate();
    
    $charger = factory( Charger::class ) -> create(['charger_id' => 29 ]);

    $charger_connector_type = factory( ChargerConnectorType::class ) -> create([
      'charger_id'        => $charger -> id,
      'connector_type_id' => 9,
    ]);

    $response = $this 
      -> actAs( $this -> user )
      -> post(  $this -> url, [
        'charger_connector_type_id' => $charger_connector_type -> id,
        'charging_type'             => ChargingTypeEnum :: FULL_CHARGE,
      ]);
          
      $responseErrors = $response -> decodeResponseJson() [ 'errors' ][ 'charger_connector_type_id' ];
      $hasError       = in_array( 'ChargerConnectorType doesn\'t have connector_type relation.', $responseErrors );
      
      $this -> assertTrue( $hasError );
      
  }

  /** @test */
  public function it_has_charging_type_error_when_not_providing_it()
  {    
    $this -> makeChargerFree();

    $response = $this
      -> actAs( $this -> user )
      -> post(  $this -> url, [
        'charger_connector_type_id' => 7
      ]);
    
    $response -> assertJsonValidationErrors( 'charging_type' );
  }


  /** @test */
  public function it_has_charging_type_errors_when_incorrect_attribute()
  {
    $this -> makeChargerFree();

    $response = $this
     -> actAs( $this -> user )
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
  public function it_has_price_error_when_charging_type_is_by_amount()
  {
    $this -> makeChargerFree();

    $charger = factory( Charger::class ) -> create([ 'charger_id' => 29 ]);

    factory( ChargerConnectorType::class ) -> create([
      'charger_id'        => $charger -> id,
      'connector_type_id' => 1
    ]);

    $response = $this 
      -> actAs( $this -> user )
      -> post(  $this -> url, [
        'charger_connector_type_id' => 1,
        'charging_type'             => ChargingTypeEnum :: BY_AMOUNT,
      ]);

      $response -> assertJsonValidationErrors('price');
  }

  /** @test */
  public function it_has_no_price_error_when_charging_type_is_not_by_amount()
  {
    $this -> makeChargerFree();

    $charger = factory( Charger::class ) -> create();

    factory( ChargerConnectorType::class ) -> create([
      'charger_id'        => $charger -> id,
      'connector_type_id' => 1
    ]);

    $response = $this 
      -> actAs( $this -> user )
      -> post(  $this -> url, [
        'charger_connector_type_id' => 1,
        'charging_type'             => ChargingTypeEnum :: FULL_CHARGE,
      ]);

      $response -> assertJsonMissingValidationErrors('price');
  }

  /** @test */
  public function charging_price_needs_to_be_numeric()
  {    
    $this -> makeChargerFree();

    $charger = factory( Charger::class ) -> create([ 'charger_id' => 29 ]);

    factory( ChargerConnectorType::class ) -> create([
      'charger_id'        => $charger -> id,
      'connector_type_id' => 1
    ]);
    
    $responseWithoutNumeric = $this 
      -> actAs( $this -> user )
      -> post(  $this -> url, [
        'charger_connector_type_id' => 1,
        'charging_type'             => ChargingTypeEnum :: BY_AMOUNT,
        'price'                     => 'mas123',
      ]);

    
    $responseWithNumeric = $this 
      -> actAs( $this -> user )
      -> post(  $this -> url, [
        'charger_connector_type_id' => 1,
        'charging_type'             => ChargingTypeEnum :: BY_AMOUNT,
        'price'                     => 2.777
      ]);
    
    $responseWithoutNumeric -> assertJsonValidationErrors('price');
    $responseWithNumeric    -> assertJsonMissingValidationErrors('price');
  }

  /** @test */
  public function it_has_has_user_card_id_error_when_not_provided()
  {
    $this -> makeChargerFree();

    $charger              = factory( Charger :: class ) -> create([ 'charger_id' => 29 ]);
    $chargerConnectorType = factory( ChargerConnectorType :: class) -> create(
      [
        'connector_type_id' => ConnectorType :: whereName( ConnectorTypeEnum :: TYPE_2 ) -> first(),
        'charger_id'        => $charger -> id,
      ]
    );

    $response = $this       
      -> actAs( $this -> user )
      -> post(  $this -> url,
        [
          'charger_connector_type_id' => $chargerConnectorType -> id,
        ]
      );

    $response -> assertJsonValidationErrors([ 'user_card_id' ]);
  }

  /** @test */
  public function it_doesnt_have_user_card_id_error_when_valid_id_is_provided()
  {
    $this -> makeChargerFree();

    $user                 = User :: first();
    $userCard             = factory( UserCard :: class ) -> create([ 'user_id' => $user -> id ]);
    $charger              = factory( Charger :: class ) -> create([ 'charger_id' => 29 ]);
    $chargerConnectorType = factory( ChargerConnectorType :: class ) -> create(
      [
        'charger_id' => $charger -> id,
        'connector_type_id' => ConnectorType :: whereName( ConnectorTypeEnum :: TYPE_2 ) -> first(),
      ]
    );
    
    $response             = $this 
      -> actAs( $this -> user )
      -> post(  $this -> url,
      [
        'charger_connector_type_id' => $chargerConnectorType -> id,
        'user_card_id'              => $userCard -> id,
      ]);

    $response -> assertJsonMissingValidationErrors([ 'user_card_id' ]);
  }

  /** @test */
  public function it_has_422_status_code_when_bad_request()
  {
    $this -> makeChargerFree();

    $charger = factory( Charger :: class ) -> create([ 'charger_id' => 29 ]);

    $chargerConnectorType = factory( ChargerConnectorType::class ) -> create([
      'charger_id'        => $charger -> id,
      'connector_type_id' => 1
    ]);

    // not providing with [charger_connector_id] array
    $response = $this
      -> actAs( $this -> user )
      -> post(  $this -> uri . 'charging/start',
      [
        'charger_connector_type_id' => $chargerConnectorType -> id,
      ]);
    
    $response -> assertStatus( 422 );
  }

  /** @test */
  public function it_has_charger_is_not_free_error_when_charger_is_not_free()
  {
    $this -> makeChargerFree();

    RealCharger :: start        ( 29, 1 );

    $charger = factory( Charger::class ) -> create([ 'charger_id' => 29 ]);

    factory( ChargerConnectorType::class ) -> create([
      'charger_id' => $charger -> id,
    ]);

    $response = $this 
      -> actAs( $this -> user )
      -> post( $this -> url, [
        'charger_connector_type_id' => 1,
        'charging_type'             => ChargingTypeEnum :: BY_AMOUNT,
        'price'                     => 50,
      ]);

    $response -> assertStatus( 400 );
    
    $response = $response -> decodeResponseJson();
    
    $this     -> assertEquals( 
      $response [ 'message' ],
      $this -> messages [ 'charger_is_not_free' ],
     );
  }

  /** @test */
  public function when_charger_is_not_free_dont_charge()
  {
    $this -> makeChargerFree();
    $this -> makeOrder();

    $response = $this 
      -> actAs( $this -> user )
      -> post(  $this -> uri . 'charging/start', [
        'charger_connector_type_id' => ChargerConnectorType::first() -> id,
        'charging_type'             => ChargingTypeEnum :: FULL_CHARGE,
        'user_card_id'              => 1,
      ]);

    $response = (object) $response -> decodeResponseJson();
    
    $this -> assertEquals( $this -> messages [ 'charger_is_not_free' ], $response -> message );
  }

  /** @test */
  public function it_returns_correct_user_card_id()
  {
    $this -> makeChargerFree();

    $user                 = User :: first();
    $userCard             = factory( UserCard :: class ) -> create([ 'user_id' => $user -> id ]);
    
    $chargerConnectorType = CCTStub :: createChargerConnectorType( ChargerTypeEnum :: LVL2 ) -> first();
    
    $response             = $this 
      -> actAs( $this -> user )
      -> post(  $this -> url, [
        'charger_connector_type_id' => $chargerConnectorType -> id,
        'charging_type'             => ChargingTypeEnum :: FULL_CHARGE,
        'user_card_id'              => $userCard -> id,
      ]);
    
    $response             = (object) $response -> decodeResponseJson();

    $this -> assertEquals( $userCard -> id, $response -> user_card_id );
  }

  /** Helpers */
  private function makeChargerFree()
  {
    Simulator :: upAndRunning( 29 );
  }

  private function makeOrder()
  {
    $charger = factory( Charger :: class ) -> create([ 'charger_id' => 29 ]);
    $connectorType = ConnectorType :: whereName( ConnectorTypeEnum :: TYPE_2 ) -> first();

    $chargerConnectorType = factory( ChargerConnectorType :: class ) -> create(
      [
        'charger_id'        => $charger -> id,
        'connector_type_id' => $connectorType -> id,
      ]
    );

    $userCard = factory( UserCard :: class ) -> create(
      [
        'user_id' => $this -> user -> id,
      ]
    );

    factory( Order :: class ) -> create(
      [
        'user_id' =>  $this -> user -> id,
        'charger_connector_type_id' => $chargerConnectorType -> id,
        'user_card_id' => $userCard -> id,
      ]
    );

    RealCharger :: start( 29, 1 );
  }
}

