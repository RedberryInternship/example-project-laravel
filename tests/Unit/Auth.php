<?php

namespace Tests\Unit;

use Tests\TestCase;

class Auth extends TestCase 
{
  protected function setUp(): void
  {
    parent :: setUp();

    $this -> url  = $this -> uri . 'login';
    $this -> meUrl = $this -> uri . 'me';

    $this -> user = $this -> createUser(
      [
        'phone_number' => '+995598317829',
        'password'     => bcrypt('datvianisebrta'),
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
  public function retrieved_response_gives_access_token(): void
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