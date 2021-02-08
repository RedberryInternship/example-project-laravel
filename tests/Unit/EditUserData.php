<?php

namespace Tests\Unit;

use Tests\TestCase;

class EditUserData extends TestCase 
{
  protected function setUp(): void
  {
    parent :: setUp();

    $this -> user = $this -> createUser(
      [
        'phone_number' => '+995591935080',
        'first_name'   => 'Merab',
        'last_name'    => 'Kostava',
        'email'        => 'merab@georgia.io',
      ]
    );

    $this -> updateUserInfoURL = $this -> uri . 'update-user-info';
    $this -> meURL = $this -> uri . 'me';
  }

  /** @test */
  public function update_phone_number(): void
  {
    $this 
      -> actAs( $this -> user )
      -> post( $this -> updateUserInfoURL, [
        'phone_number' => '+995591935127',
      ]) 
      -> assertOk();
    
    $this -> user -> refresh();

    $this -> assertEquals( '+995591935127', $this -> user -> phone_number );
  }

  /** @test */
  public function update_first_name(): void
  {
    $this 
      -> actAs( $this -> user )
      -> post( $this -> updateUserInfoURL, [
        'first_name' => 'Ilia',
      ]) 
      -> assertOk();
  
    $this -> user -> refresh();

    $this -> assertEquals( 'Ilia', $this -> user -> first_name ); 
  }
  
  /** @test */
  public function update_last_name(): void
  {
    $this 
      -> actAs( $this -> user )
      -> post( $this -> updateUserInfoURL, [
        'last_name' => 'Martali',
      ]) 
      -> assertOk();
  
    $this -> user -> refresh();

    $this -> assertEquals( 'Martali', $this -> user -> last_name ); 
  }

  /** @test */
  public function update_email(): void
  {
    $this 
      -> actAs( $this -> user )
      -> post( $this -> updateUserInfoURL, [
        'email' => 'ilia@sinodi.ru',
      ]) 
      -> assertOk();
  
    $this -> user -> refresh();

    $this -> assertEquals( 'ilia@sinodi.ru', $this -> user -> email ); 
  }

    /** @test */
    public function batch_update(): void
    {
      $this 
        -> actAs( $this -> user )
        -> post( $this -> updateUserInfoURL, [
          'phone_number' => '+995591935127',
          'first_name' => 'Ilia',
          'last_name' => 'Martali',
          'email' => 'ilia@sinodi.ru',
    
        ]) 
        -> assertOk();
  
      $this -> user -> refresh();
    
      $this -> assertEquals( '+995591935127',   $this -> user -> phone_number );
      $this -> assertEquals( 'Ilia',            $this -> user -> first_name );
      $this -> assertEquals( 'Martali',         $this -> user -> last_name );
      $this -> assertEquals( 'ilia@sinodi.ru',  $this -> user -> email );
    }
}