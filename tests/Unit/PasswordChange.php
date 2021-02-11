<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class PasswordChange extends TestCase
{
  protected function setUp(): void
  {
    parent :: setUp();

    $this -> resetPasswordURL   = $this -> uri . 'reset-password';
    $this -> updatePasswordURL  = $this -> uri . 'edit-password';

    $this -> currentPassword    = 'shaifrtxiala';
    $this -> phoneNumber        = '+995591935080';
    $this -> user = $this -> createUser(
      [
        'phone_number' => $this -> phoneNumber,
        'password'     => bcrypt( $this -> currentPassword ),
      ]
    );
  }

  /** @test */
  public function reset_password_gives_ok(): void
  {
    $NEW_PASSWORD = 'giabarabanshiki';

    $this 
      -> post( $this -> resetPasswordURL, 
      [
        'phone_number' => $this -> phoneNumber,
        'password'     => $NEW_PASSWORD,
      ])
      -> assertOk();

    $this -> user -> refresh();
    
    $changed = Hash :: check( $NEW_PASSWORD, $this -> user -> password );
    $this -> assertTrue( $changed );
  }

  /** @test */
  public function update_password_gives_ok(): void
  {
    $NEW_PASSWORD = 'giabarabanshiki';

    $this 
      -> actAs( $this -> user ) 
      -> post( $this -> updatePasswordURL, 
        [
          'phone_number' => $this -> phoneNumber,
          'new_password' => $NEW_PASSWORD,
          'old_password' => $this -> currentPassword,
        ]
      ) 
      -> assertOk();
  }
}