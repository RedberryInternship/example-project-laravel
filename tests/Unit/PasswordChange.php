<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class PasswordChange extends TestCase
{
  protected function setUp(): void
  {
    parent :: setUp();

    $this -> resetPasswordURL = $this -> uri . 'reset-password';
    $this -> updatePasswordURL = $this -> uri . 'edit-password';

    $this -> currentPassword = 'shaifrtxiala';
    $this -> user = $this -> createUser(
      [
        'phone_number' => '+995591935080',
        'password'     => bcrypt( $this -> currentPassword ),
      ]
    );
  }

  /** @test */
  public function reset_password_gives_ok(): void
  {
    $NEW_PASSWORD = 'giabarabanshki';

    $this 
      -> post( $this -> resetPasswordURL, 
      [
        'phone_number' => '+995591935080',
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
    $NEW_PASSWORD = 'giabarabanshki';

    // $this -> 
  }
}