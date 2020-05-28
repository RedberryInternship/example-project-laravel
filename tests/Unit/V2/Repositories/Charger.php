<?php

namespace Tests\Unit\V2\Repositories;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

use App\Enums\OrderStatus;

use App\ChargerConnectorType;
use App\Charger as ChargerModel;
use App\Order;

class Charger extends TestCase
{ 
  use RefreshDatabase;
  
  private $charger;

  protected function setUp(): void
  {
    parent::setUp();

    $this -> charger = factory( ChargerModel :: class ) -> create();
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
  public function charger_can_get_free_charger_ids()
  {
    DB :: table( 'chargers' ) -> delete();

    $charger1               = factory( ChargerModel :: class ) -> create(); // free
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
        'charging_status'           => OrderStatus :: FINISHED, 
        ]
      );
    
    $charger2               = factory( ChargerModel :: class ) -> create(); // free
    $charger2ConnectorType  = factory( ChargerConnectorType :: class )  
      -> create
        (
          [ 
            'charger_id' => $charger2 -> id, 
          ]
        ); 

    $charger3               = factory( ChargerModel :: class ) -> create(); // not free
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
            'charging_status'           => OrderStatus :: FINISHED,
          ]
        );
    $order3                 = factory( Order :: class ) 
      -> create
        (
          [ 
            'charger_connector_type_id' => $charger3ConnectorType2 -> id, 
            'charging_status'           => OrderStatus :: INITIATED, 
          ]
        );
    
    
    $this -> assertTrue(    ChargerModel :: isChargerFree( $charger1 -> id ) );
    $this -> assertTrue(    ChargerModel :: isChargerFree( $charger2 -> id ) );
    $this -> assertTrue( !  ChargerModel :: isChargerFree( $charger3 -> id ) );
  }
}