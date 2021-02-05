<?php

namespace Tests\Unit;

use App\User;
use App\Contact;
use Tests\TestCase;
use App\ContactMessage;

class SendSmsToAdmin extends TestCase {
  protected function setUp(): void
  {
    parent :: setUp();

    $this -> url = $this -> uri . 'contact-message';
    factory(Contact :: class) -> create();
    $this -> user = factory(User :: class) -> create();
  }

  /** @test */
  public function send_gives_ok(): void
  {
    $this
      -> post(
        $this -> url,
        [
          'message' => 'Hello from Dorne',
        ]
      ) 
      -> assertOk();
  }
  
  /** @test */
  public function send_gives_not_ok(): void
  {
    $this
      -> post(
        $this -> url,
        [],
      ) 
      -> assertStatus(422);
  }

  /** @test */
  public function user_is_saved_with_sms(): void
  {
    $this 
      -> actAs( $this -> user )
      -> post(
        $this -> url,
        [
          'message' => 'Mom, I\'m here!',
        ]
      );

    $messageUserId = ContactMessage :: first() -> user_id;

    $this -> assertEquals($this -> user -> id, $messageUserId);
  }
}