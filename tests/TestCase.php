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
        $this -> uri = config('app')['uri'];

        app() -> singleton( 'charger'   , MishasCharger :: class );
        app() -> singleton( 'simulator' , Simulator     :: class );

        $this -> artisan('config:clear');
        $this -> artisan('route:clear');
        $this -> artisan('cache:clear');
        $this -> artisan('view:clear');
        $this -> artisan('migrate:fresh');
    }

    /**
     * Custom actingAs implementation for jwt.
     *
     * @param   \App\User $user
     * @return  object
     */
    public function actAs( $user )
    {
       $token = JWTAuth::fromUser( $user );
       $this -> withHeader( 'Authorization', 'Bearer '. $token );

       return $this;
    }

}
