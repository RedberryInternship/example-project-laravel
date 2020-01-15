<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\User;

class UserTest extends TestCase
{
	public    $user;
    protected $request;
	
    public function setUp():void
    {
    	parent::setUp();

    	$this -> user     = factory(User::class)->create();

        $this -> request  = ['fname' => 'gela', 'lname' => 'pataraia'];
    }
    
    public function testUserKeys()
    {   
        $columns    = Schema::getColumnListing('users');
        
        $keys       = [];
        
        foreach($columns as $v)
        {
            array_push($keys, $v);
        }

        foreach($this -> request as $key => $value)
        {
            $this->assertContains($key, $keys);
        }

        $user           = $this -> user;

        $responseJson   = $this -> actingAs($user, 'api') -> json('POST','/api/app/V1/update-user-info',$this -> request);

        $responseJson->assertStatus(200);
    }
}
