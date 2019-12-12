<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class PasswordRessetTest extends TestCase
{
	public $user;
    public function setUp():void
    {
    	parent::setUp();
    	$this -> user = factory(User::class)->create();
    }
    public function testPasswordResset()
    {
       	$user 		  = $this -> user; 
    	$request 	  = [
    		'phone_number' => $user -> phone_number,
    		'password' 	   => 'password'
    	];
    	$responseJson = $this -> post('/api/app/V1/reset-password', $request);
    	$responseJson -> assertStatus(200);
    }
    public function testPasswordRessetFailed()
    {
    	$request 	  = [
    		'phone_number' => 2321312312312,
    		'password' 	   => 'password'
    	];
    	$responseJson = $this -> post('/api/app/V1/reset-password', $request);
    	$responseJson -> assertStatus(401);
    }    
}
