<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Enums\ConnectorType as EnumsConnectorType;
use App\Library\Testing\MishasCharger;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Library\Testing\Simulator;
use App\Library\Entities\Helper;
use App\Enums\Role as RoleEnum;
use App\Role;
use App\User;


abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Set up...
     */
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
     * Tear down...
     */
    protected function tearDown(): void
    {
        Helper :: removeTmpExcelFiles();
        parent :: tearDown();
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

    /**
     * Create business role.
     * 
     * @return Role
     */
    public function createBusinessRole(): Role
    {
        return factory(Role::class)->create(
            [
                'name' => RoleEnum::BUSINESS,
            ]
        );
    }

    /**
     * Create connector types.
     * 
     * @return void
     */
    public function createConnectorTypes(): void
    {
        DB::table('connector_types')->insert(
            [
                [
                    'name' => EnumsConnectorType::CHADEMO,
                ],
                [
                    'name' => EnumsConnectorType::COMBO_2,
                ],
                [
                    'name' => EnumsConnectorType::TYPE_2,
                ],
            ],
        );
    }
}
