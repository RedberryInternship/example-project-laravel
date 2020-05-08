<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Custom actingAs implementation for jwt.
     * 
     * @param   \App\User $user
     * @return  void
     */
    public function actAs( $user )
    {
       $token = JWTAuth::fromUser( $user );
       $this -> withHeader( 'Authorization', 'Bearer '. $token );

       return $this;
    }

}
