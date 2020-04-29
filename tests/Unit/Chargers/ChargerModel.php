<?php

namespace Tests\Unit\Chargers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;


use App\ChargerConnectorType;
use App\ConnectorType;
use App\Charger;
use App\Order;

class ChargerModel extends TestCase
{ 
  use RefreshDatabase;
  
  private $charger;

  protected function setUp(): void
  {
    parent::setUp();

    $this -> charger = factory( Charger :: class ) -> create();
  }

  protected function tearDown(): void
  {
    $this -> beforeApplicationDestroyed( function () {
      foreach( DB :: getConnections() as $connection)
      {
        $connection -> disconnect();
      }
    });

    parent :: tearDown();
  }

  /** @test */
  public function charger_has_charger_connector_types()
  {
    $charger = $this -> charger;
    
    factory( ChargerConnectorType :: class ) -> create([ 'charger_id' => $charger -> id ]); 
    
    $charger -> load( 'charger_connector_types' );
    
    $this -> assertTrue( !! $charger -> charger_connector_types );
  }

  /** @test */
  public function charger_can_get_free_charger_ids()
  {
    DB :: table( 'chargers' ) -> delete();

    $charger1               = factory( Charger :: class ) -> create(); // free
    $charger1ConnectorType1 = factory( ChargerConnectorType :: class )  
      -> create
      (
        [ 
          'charger_id' => $charger1 -> id,
        ]
      ); 
    
    $order1                 = factory( Order :: class ) 
      -> create
      (
        [ 
        'charger_connector_type_id' => $charger1ConnectorType1 -> id, 
        'charging_status' => 'FINISHED', 
        ]
      );
    
    $charger2               = factory( Charger :: class ) -> create(); // free
    $charger2ConnectorType = factory( ChargerConnectorType :: class )  
      -> create
        (
          [ 
            'charger_id' => $charger2 -> id, 
          ]
        ); 

    $charger3               = factory( Charger :: class ) -> create(); // not free
    $charger3ConnectorType1 = factory( ChargerConnectorType :: class )  
      -> create
      (
        [ 
          'charger_id' => $charger3 -> id, 
        ]
      );

    $charger3ConnectorType2 = factory( ChargerConnectorType :: class ) 
      -> create
      (
        [ 
          'charger_id' => $charger3 -> id,
        ]
      ); 
    $order3                 = factory( Order :: class ) 
      -> create
        (
          [ 
            'charger_connector_type_id' => $charger3ConnectorType1 -> id, 
            'charging_status' => 'FINISHED',
          ]
        );
    $order3                 = factory( Order :: class ) 
      -> create
        (
          [ 
            'charger_connector_type_id' => $charger3ConnectorType2 -> id, 
            'charging_status' => 'INITIATED', 
          ]
        );
    
    
    $this -> assertTrue(    Charger :: isChargerFree( $charger1 -> id ) );
    $this -> assertTrue(    Charger :: isChargerFree( $charger2 -> id ) );
    $this -> assertTrue( !  Charger :: isChargerFree( $charger3 -> id ) );
  }
}