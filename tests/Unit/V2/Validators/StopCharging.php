<?php

namespace Tests\Unit\V2\Validators;

use Tests\TestCase;
use App\User;

class StopCharging extends TestCase
{
  private $uri;
  private $url;
  private $user;

  protected function setUp(): void
  {
    parent :: setUp();

    $this -> artisan( 'migrate:fresh' );
    $this -> uri  = config( 'app' )[ 'uri' ];
    $this -> url  = $this -> uri . 'charging/stop';
    $this -> user = factory( User :: class ) -> create();
  }

  /** @test */
  public function it_has_an_error_when_order_id_is_not_provided()
  {
    $response = $this 
      -> actAs( $this -> user )
      -> post ( $this -> url  );
    
    $response -> assertStatus(422);
  }
}