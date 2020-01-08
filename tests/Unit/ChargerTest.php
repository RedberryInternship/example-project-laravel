<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Charger;

class ChargerTest extends TestCase
{
	public $charger;
    public function setUp():void
    {
    	parent::setUp();
    	$this -> charger  = factory(Charger::class)->create();
    }
    public function testChargers()
    {
    	$jsonResponse = $this -> json("GET","/api/app/V1/chargers");
    	$jsonResponse -> assertStatus(200);
    }
    public function testSingleCharger()
    {	
    	$charger_id   = $this -> charger -> id;
    	$jsonResponse = $this -> json("GET","/api/app/V1/charger/".$charger_id);
    	$jsonResponse -> assertStatus(200);
    }
}
