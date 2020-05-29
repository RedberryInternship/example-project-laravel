<?php

namespace Tests\Unit\ChargerConnectorTypes;

use Tests\TestCase;


use App\ChargerConnectorType;
use App\FastChargingPrice;
use App\ChargingPrice;
use App\Charger;
use App\Order;

class Relationships extends TestCase
{

  private $chargerConnectorType;

  protected function setUp(): void
  {
    parent::setUp();
    $this -> artisan( 'migrate' );
    $this -> chargerConnectorType = factory( ChargerConnectorType :: class ) -> create();
  }

  /** @test */
  public function it_has_connector_type()
  {
    $this -> chargerConnectorType -> load( 'connector_type' );
    $this -> assertTrue( !! $this -> chargerConnectorType -> connector_type );
  }

  /** @test */
  public function it_has_charger()
  {
    $charger = factory( Charger :: class ) -> create();
    
    $this -> chargerConnectorType -> update([ 'charger_id' => $charger -> id ]);
    $this -> chargerConnectorType -> load( 'charger' );

    $this -> assertTrue( !! $this -> chargerConnectorType -> charger );
  }

  /** @test */
  public function it_has_charging_prices()
  {
    $chargerConnectorType = $this -> chargerConnectorType;

    factory( ChargingPrice :: class ) -> create([ 'charger_connector_type_id' => $chargerConnectorType -> id ]);
    factory( ChargingPrice :: class ) -> create([ 'charger_connector_type_id' => $chargerConnectorType -> id ]);
    
    $chargerConnectorType -> load( 'charging_prices' );

    $this -> assertTrue( !! $chargerConnectorType -> charging_prices );
    $this -> assertCount( 2, $chargerConnectorType -> charging_prices );
  }

  /** @test */
  public function it_has_fast_charging_prices()
  {
    $chargerConnectorType = $this -> chargerConnectorType;

    factory( FastChargingPrice :: class ) -> create([ 'charger_connector_type_id' => $chargerConnectorType -> id ]);
    factory( FastChargingPrice :: class ) -> create([ 'charger_connector_type_id' => $chargerConnectorType -> id ]);
    factory( FastChargingPrice :: class ) -> create([ 'charger_connector_type_id' => $chargerConnectorType -> id ]);

    $chargerConnectorType -> load( 'fast_charging_prices' );

    $this -> assertTrue( !! $chargerConnectorType -> fast_charging_prices );
  }

  /** @test */
  public function it_has_order()
  {
    $chargerConnectorType = $this -> chargerConnectorType;

    factory( Order :: class ) -> create([ 'charger_connector_type_id' => $chargerConnectorType -> id ]);
    factory( Order :: class ) -> create([ 'charger_connector_type_id' => $chargerConnectorType -> id ]);
    
    $chargerConnectorType -> load( 'orders' );
    
    $this -> assertCount( 2, $chargerConnectorType -> orders );
  }
}