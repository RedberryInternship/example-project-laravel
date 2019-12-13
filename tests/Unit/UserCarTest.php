<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\UserCarModel;

class UserCarTest extends TestCase
{
	public $user;
    public function setUp():void
    {
    	parent::setUp();
    	$this -> user = factory(User::class)->create();
    }
    public function testAddUserCar()
    {
    	$user = $this -> user;
    	$request = [
    		'car_model_id' => 9
    	];
    	$jsonResponse = $this -> actingAs($user, 'api') -> post('/api/app/V1/add-user-car', $request);
    	$jsonResponse -> assertStatus(200);
    }
   	public function testAddUserCarInserted()
   	{
   	   	$user = $this -> user;
    	$request = [
    		'car_model_id' => 8
    	];
    	$jsonResponse = $this -> actingAs($user, 'api') -> post('/api/app/V1/add-user-car', $request);	
    	$this -> assertDatabaseHas('user_car_models',[
    		'user_id' 	=> $user -> id,
    		'model_id'  => $request['car_model_id']
     	]);
   	}
}
