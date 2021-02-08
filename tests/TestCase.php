<?php

namespace Tests;

use App\Role;
use App\User;
use App\Enums\Role as RoleEnum;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

use App\Library\Testing\MishasCharger;
use App\Library\Testing\Simulator;

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
     * @return  self
     */
    public function actAs( $user )
    {
       $token = JWTAuth::fromUser( $user );
       $this -> withHeader( 'Authorization', 'Bearer '. $token );

       return $this;
    }

    /**
     * Create regular user.
     * 
     * @param array $userAttributes
     * @return \App\User
     */
    public function createUser($userAttributes = [])
    {
        $role = factory(Role :: class) -> create(
            [
              'name' => RoleEnum :: REGULAR,
            ]
        );

        $userDefaultAttributes = [
                'phone_number' => '+995598317829',
                'password'     => bcrypt('datvianisebrta'),
                'role_id'      => $role -> id,
        ];


        return factory( User :: class ) -> create(
            array_merge($userDefaultAttributes, $userAttributes),
        );
    }
}
