<?php

namespace Tests\Unit\Kilowatts;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

use App\Kilowatt;

class KilowattEntity extends TestCase
{
 
  protected $kilowatt;

  protected function setUp(): void
  {
    parent :: setUp();
    $this -> artisan('migrate:fresh');
    $this -> kilowatt = factory( Kilowatt :: class ) -> create();
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
  public function it_can_set_charging_power()
  {
    $kilowatt = $this -> kilowatt;

    $kilowatt -> setChargingPower( 1000 );
    $kilowatt -> refresh();

    $this -> assertEquals( 1000, $kilowatt -> getChargingPower() );
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