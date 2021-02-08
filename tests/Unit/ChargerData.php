<?php

namespace Tests\Unit;

use App\Charger;
use Tests\TestCase;

class ChargerData extends TestCase {
  protected function setUp(): void
  {
    parent :: setUp();
    factory( Charger :: class, 3 ) -> create();
    $this -> getChargersUrl = $this -> uri . 'chargers';
    $this -> getChargerUrl = $this -> uri . 'charger/';
  }

  /** @test */
  public function get_chargers_gives_ok(): void
  {
    $this -> get( $this -> getChargersUrl ) -> assertOk();
  }

  /** @test */
  public function get_charger_gives_ok(): void
  {
    $this -> get( $this -> getChargerUrl . '1') -> assertOk();
    $this -> get( $this -> getChargerUrl . '2') -> assertOk();
    $this -> get( $this -> getChargerUrl . '3') -> assertOk();
  }

  /** @test */
  public function get_charger_gives_failure(): void
  {
    $this -> get( $this -> getChargerUrl . '15213' ) -> assertStatus(404);
  }
}