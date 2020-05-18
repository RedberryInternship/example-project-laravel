<?php

namespace Tests\Unit\ChargingPrices;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

use App\ChargerConnectorType;
use App\ChargingPrice;

class Relationships extends TestCase
{
  
  protected function setUp(): void
  {
    parent :: setUp();
    $this -> artisan( 'migrate:fresh' );
  }

  protected function tearDown(): void
  {
    $this -> beforeApplicationDestroyed( function() {
      $connections = DB::getConnections();

      foreach( $connections as $conn )
      {
        $conn -> disconnect();
      }
    });
    parent :: tearDown();
  }
  
  /** @test */
  public function charging_price_belongs_to_charger_connector_type()
  {
      $chargerConnectorType = factory( ChargerConnectorType :: class ) -> create();
      
      $chargingPrice = factory( ChargingPrice :: class ) -> create(
        [ 
          'charger_connector_type_id' => $chargerConnectorType -> id, 
        ]
      );

      $this -> assertTrue   ( !! $chargingPrice -> charger_connector_type );
      $this -> assertEquals ( 
        $chargerConnectorType -> id, 
        $chargingPrice -> charger_connector_type -> id, 
      );
  }
}