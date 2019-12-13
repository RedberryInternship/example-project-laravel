<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class RegisterTest extends TestCase
{
	use RefreshDatabase;
	public $user;
    public function setUp():void
    {
    	parent::setUp();
    	$this -> user = factory(User::class)->create();
    }

    public function testRegisterValidationFails()
    {
    	$user    = $this -> user;
    	$request = [
    		'first_name'   =>  $user -> first_name,
    		'last_name'    =>  $user -> last_name,
    		'phone_number' =>  $user -> phone_number,
    		'role'	 	   =>  1,
    		'email'		   =>  $user -> email,
    		'password' 	   =>  'password'
    	];
        $responseJson = $this -> post('/api/app/V1/register/', $request);
    	$responseJson -> assertStatus(400);
    }
   	public function testRegister()
    {
    	$request = [
    		'first_name'   =>  'datvi',
    		'last_name'    =>  'nesvi',
    		'phone_number' =>  '3232323',
    		'role'	 	   =>  1,
    		'email'		   =>  'fefe@defe.ge',
    		'password' 	   =>  'password'
    	];
        $responseJson = $this -> post('/api/app/V1/register/', $request);
    	$responseJson -> assertStatus(200);
    }

    public function testSmsCode()
    {
    	$request = [
    		'phone_number' => '77738219'
    	];
    	$responseJson = $this -> post('/api/app/V1/send-sms-code', $request);
    	$responseJson -> assertStatus(200);
    }

  	public function testVerifyCode()
    {
    	$request = [
    		'phone_number' => '77738219',
    		'code'		   => '3030'
    	];
    	$responseJson = $this -> post('/api/app/V1/verify-code', $request);
    	$this -> assertEquals('401', $responseJson -> original['status']);
    }
    
}
