<?php

namespace Tests\Unit\Chargers;

use Tests\TestCase;
use App\Facades\Charger;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Charger as OurCharger;
use App\Facades\ChargerSyncer;

class Sync extends TestCase
{

  use RefreshDatabase;

  /** @test */
  public function do_we_get_the_chargers()
  {
    
    $response = Charger::all();
    
    $this -> assertTrue($response['status_code'] == 700);
    
    $chargers = $response['data']->data->chargers;
    $this -> assertTrue(count($chargers) > 0);
  }

  /** @test */
  public function data_can_be_inserted()
  {
    ChargerSyncer::insertOrUpdate();

    $this -> assertTrue(OurCharger::count() > 0);
  }

  /** @test */

}