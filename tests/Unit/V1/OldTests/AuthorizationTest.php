<?php

namespace Tests\Unit\OldTests;

use Tests\TestCase;
use App\User;

class AuthorizationTest extends TestCase
{
	public $user;
    public function setUp():void
    {
    	parent::setUp();

    	$this -> user = factory(User::class)->create();
    }

    public function testLogin()
    {
    	$user 		  = $this -> user; 
    	$request 	  = [
    		'phone_number' => $user -> phone_number,
    		'password' 	   => 'password'
    	];
    	$responseJson = $this -> post('/api/app/V1/login', $request);
    	$responseJson -> assertStatus(200);
    }

   	public function testLoginFailed()
   	{
   		$user 		  = $this -> user; 
    	$request 	  = [
    		'phone_number' => $user -> phone_number,
    		'password' 	   => '12'
    	];
    	$responseJson = $this -> post('/api/app/V1/login', $request);
		$responseJson -> assertStatus(401);   	
	}
	public function testLoginToken()
	{
		$user 		  = $this -> user; 
    	$request 	  = [
    		'phone_number' => $user -> phone_number,
    		'password' 	   => 'password'
    	];
    	$responseJson = $this -> post('/api/app/V1/login', $request);
    	$this -> assertEquals('bearer', $responseJson -> original['token_type']);
	}
}
