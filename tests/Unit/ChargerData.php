<?php

namespace Tests\Unit;

use App\Charger;
use App\ChargerConnectorType;
use App\Order;
use Tests\TestCase;

class ChargerData extends TestCase 
{
  protected function setUp(): void
  {
    parent :: setUp();
    factory( Charger :: class, 3 ) -> create();
    $this -> getChargersURL = $this -> uri . 'chargers';
    $this -> getChargerURL = $this -> uri . 'charger/';
    $this -> getUserChargersURL = $this -> uri . 'user-chargers';
    $this -> user = $this -> createUser();
  }

  /** @test */
  public function get_chargers_gives_ok(): void
  {
    $this -> get( $this -> getChargersURL ) -> assertOk();
  }

  /** @test */
  public function get_charger_gives_ok(): void
  {
    $this -> get( $this -> getChargerURL . '1') -> assertOk();
    $this -> get( $this -> getChargerURL . '2') -> assertOk();
    $this -> get( $this -> getChargerURL . '3') -> assertOk();
  }

  /** @test */
  public function get_charger_gives_failure(): void
  {
    $this -> get( $this -> getChargerURL . '15213' ) -> assertStatus(404);
  }

  /** @test */
  public function user_chargers_gives_ok(): void
  {
    factory( Charger :: class, 4 ) -> create()
      -> each( function ( $charger ) {
        $chargerConnectorType = factory( ChargerConnectorType :: class ) -> create(
          [
            'charger_id' => $charger -> id,
          ]
        );

        factory( Order :: class ) -> create(
          [
            'charger_connector_type_id' => $chargerConnectorType -> id,
            'user_id' => $this -> user -> id,
          ]
        );
      });

    $this 
      -> actAs( $this -> user )
      -> get( $this ->  getUserChargersURL )
      -> assertOk()
      -> assertJsonCount(3, 'data');
    
    $this 
      -> actAs( $this -> user )
      -> get( $this ->  getUserChargersURL . '/' . 4 )
      -> assertOk()
      -> assertJsonCount(4, 'data');
  }
}