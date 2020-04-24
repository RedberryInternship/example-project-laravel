<?php

namespace Tests\Unit\OldTests;

use Tests\TestCase;
use App\User;
use App\CarModel;

class UserCarTest extends TestCase
{
	public $user;
	public $user_car_model;
	public $model;
    public function setUp():void
    {
    	parent::setUp();
    	$this -> user 		= factory(User::class)->create();
    	$this -> model  	= factory(CarModel::class)->create();
    	$this -> user -> car_models() -> attach($this -> model);
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
   		foreach($user -> car_models -> toArray() as $user_car_model)
   		{
   			$model_id = $user_car_model['pivot']['model_id'];
   		}
    	$request = [
    		'car_model_id' => $model_id
    	];
    	$jsonResponse = $this -> actingAs($user, 'api') -> post('/api/app/V1/add-user-car', $request);	
    	$this -> assertDatabaseHas('user_car_models',[
    		'user_id' 	=> $user -> id,
    		'model_id'  => $request['car_model_id']
     	]);
   	}
   	public function testDeleteUserCar()
   	{
   		$user   	    = $this -> user;
   		foreach($user -> car_models -> toArray() as $user_car_model)
   		{
   			$model_id = $user_car_model['pivot']['model_id'];
   		}
   		$request = [
   			'model_id' => $model_id
   		];
   		$jsonResponse    = $this -> actingAs($user, 'api') -> post('/api/app/V1/delete-user-car/', $request);
   		$jsonResponse -> assertStatus(200);
   	}
}
