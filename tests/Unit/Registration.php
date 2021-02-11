<?php

namespace Tests\Unit;

use Tests\TestCase;

class Registration extends TestCase
{
  protected function setUp(): void
  {
    parent :: setUp();

    $this -> registerURL = $this -> uri .  'register';
  }

  /** @test */
  public function is_ok(): void
  {
    $this 
      -> post( $this -> registerURL, 
        [
          'first_name' => 'Gela',
          'last_name' => 'Nagvliani',
          'email' => 'gela@nagvli.ru',
          'phone_number' => '+99559728912',
          'password' => 'tarielamachukeli!',
        ] 
      )
      -> assertOk();
  }

  /** @test */
  public function registration_validation_has_errors(): void
  {
    $this 
      -> post( $this -> registerURL, 
        [
          'first_name' => 'Gela',
          // 'last_name' => 'Nagvliani',
          'email' => 'gela@nagvli.ru',
          'phone_number' => '+99559728912',
          // 'password' => 'tarielamachukeli!',
        ] 
      )
      -> assertJsonValidationErrors(['password', 'last_name'], 'error');
  }

  /** @test */
  public function user_definitely_gets_added(): void
  {
    $this 
    -> post( $this -> registerURL, 
      [
        'first_name' => 'Gela',
        'last_name' => 'Nagvliani',
        'email' => 'gela@nagvli.ru',
        'phone_number' => '+99559728912',
        'password' => 'tarielamachukeli!',
      ] 
    );

    $this -> assertDatabaseHas('users', 
      [
        'first_name' => 'Gela',
        'last_name' => 'Nagvliani',
        'email' => 'gela@nagvli.ru',
        'phone_number' => '+99559728912',
      ]
    );
  }
}