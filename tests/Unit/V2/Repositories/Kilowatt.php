<?php

namespace Tests\Unit\V2\Repositories;

use Illuminate\Support\Facades\DB;

use Tests\TestCase;

class Kilowatt extends TestCase
{
  protected $kilowatt;

  protected function setUp(): void
  {
    parent :: setUp();
    $this -> artisan('migrate:fresh');
    $this -> kilowatt = factory( \App\Kilowatt :: class ) -> create();
  }

  protected function tearDown(): void
  {
    $this -> beforeApplicationDestroyed(function () {
      $connections = DB :: getConnections();

      foreach( $connections as $connection )
      {
        $connection -> disconnect();
      }
    });

    parent :: setUp();
  }
  
  /** @test */
  public function it_can_update_consumed_kilowatts()
  {
    $kilowatt = $this -> kilowatt;

    $kilowatt -> update([ 'consumed' => 124 ]);
    $this     -> assertEquals( 124, $kilowatt -> consumed );
    
    $kilowatt -> updateConsumedKilowatts( 125 );
    $this     -> assertEquals( 0.125, $kilowatt -> consumed );
  }
}