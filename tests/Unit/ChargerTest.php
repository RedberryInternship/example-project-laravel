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
    	$jsonResponse = $this -> json("GET","/api/app/V1/get-chargers");
    	$jsonResponse -> assertStatus(200);
    }
    public function testSingleCharger()
    {	
    	$charger_id   = $this -> charger -> id;
    	$jsonResponse = $this -> json("GET","/api/app/V1/get-charger/".$charger_id);
    	$jsonResponse -> assertStatus(200);
    }
    public function testChargerJsonStructure()
    {
    	$jsonResponse = $this -> json("GET","/api/app/V1/get-chargers");
    	$jsonResponse -> assertJsonStructure([
    		'charger' => [
    			'chargers_array' => [
    				'*' => [
    					'id',
	    				'old_id',
	    				'name' => [
	    					'ka',
	    					'ru',
	    					'en'
	    				],
	    				'charger_attributes' => [
	    				],
	    				'tags_array' => [
	    				],
	    				'code',
	    				'description' => [
	    					'ka',
	    					'ru',
	    					'en'
	    				],
	    				'user_id',
	    				'location' => [
	    					'ka',
	    					'ru',
	    					'en'
	    				],
	    				'public',
	    				'active',
	    				'lat',
	    				'lng',
	    				'charger_group_id',
	    				'iban',
	    				'last_update'
    				]
    			],
    		]
    	]);
    }
}
