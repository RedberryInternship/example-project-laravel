<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Charger;
use App\User;

class FavoritesTest extends TestCase
{
	public $user;
	public $charger;
	
    public function setUp():void
    {
    	parent::setUp();
    	$this -> user 		= factory(User::class)->create();
    	$this -> charger 	= factory(Charger::class)->create();
    }
    public function testCheckTokenIsValidAndLogin()
    {
    	$user 	  	  = $this -> user;
		$responseJson = $this -> actingAs($user, 'api') -> post('/api/app/V1/add-favorite');
		$responseJson->assertStatus(200);
    }

    public function testAddFavorite()
    {
    	$user 	 = $this -> user;
    	$charger = $this -> charger;
    	$request 	  = [
    		'charger_id'   => $charger -> id
    	];
    	$jsonResponse = $this -> actingAs($user, 'api') -> post('/api/app/V1/add-favorite', $request);
		$jsonResponse->assertStatus(200);
    }	

    public function testRemoveFavorite()
    {
     	$user 	 = $this -> user;
    	$charger = $this -> charger;
    	$request 	  = [
    		'charger_id'   => $charger -> id
    	];
    	$jsonResponse = $this -> actingAs($user, 'api') -> post('/api/app/V1/remove-favorite', $request);
		$jsonResponse->assertStatus(200);
    }

}
