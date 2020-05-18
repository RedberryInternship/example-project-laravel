<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\Library\Chargers\Testing\MishasCharger;
use App\Library\Chargers\Testing\Simulator;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent :: setUp();

        app() -> singleton( 'charger'   , MishasCharger :: class );
        app() -> singleton( 'simulator' , Simulator     :: class );
    }

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
