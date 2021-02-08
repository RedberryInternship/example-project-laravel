<?php

namespace Tests\Unit;

use App\Enums\Role as RoleEnum;
use App\Role;
use App\User;
use Tests\TestCase;

class Auth extends TestCase {
  protected function setUp(): void
  {
    parent :: setUp();

    $this -> url  = $this -> uri . 'login';
    $this -> meUrl = $this -> uri . 'me';

    $role = factory(Role :: class) -> create(
      [
        'name' => RoleEnum :: REGULAR,
      ]
    );

    $this -> user = factory( User :: class ) -> create(
      [
        'phone_number' => '+995598317829',
        'password'     => bcrypt('datvianisebrta'),
        'role_id'      => $role -> id,
      ]
    );
  }

  /** @test */
  public function is_ok(): void
  {
    $this -> post( $this -> url, [ 
      'phone_number' => '+995598317829',
      'password'     => 'datvianisebrta',
      ]
    ) -> assertOk();
  }
  
  /** @test */
  public function could_not_found(): void
  {
    $this -> post( $this -> url, [ 
      'phone_number' => '+995598317829',
      'password'     => '--||--',
      ]
    ) -> assertStatus(403);
  }

  /** @test */
  public function retrieved_token_gives_me(): void
  {
    $JWToken = $this -> post( $this -> url, [ 
      'phone_number' => '+995598317829',
      'password'     => 'datvianisebrta',
      ]
    ) -> decodeResponseJson('access_token');


    $this 
      -> withHeader('Authorization', 'Bearer ' . $JWToken )
      -> get( $this -> meUrl) -> assertOk();
  }
}