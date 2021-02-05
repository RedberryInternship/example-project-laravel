<?php

namespace Tests\Unit;

use Tests\TestCase;

class DefaultLocation extends TestCase {
  protected function setUp(): void
  {
    parent :: setUp();
    $this -> url = $this -> uri . 'geo-ip';
  }

  /** @test */
  public function is_ok(): void 
  {
    $this -> get( $this -> url ) -> assertOk();
  }

  /** @test */
  public function location_structure(): void 
  {
    $this -> get( $this -> url ) -> assertJsonStructure(
      [
        'Latitude',
        'Longitude',
      ]
    );
  }
}