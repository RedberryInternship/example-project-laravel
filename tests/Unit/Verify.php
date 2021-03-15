<?php

namespace Test\Unit;

use Tests\TestCase;
use App\Enums\AppFormType;
use App\Library\Testing\SMS;

class Verify extends TestCase
{
  /**
   * Replace Sms provider with mocker.
   */
  protected function setUp(): void
  {
    parent :: setUp();

    $this -> app -> bind( 'SMSProvider', SMS :: class );
    $this -> sendSMSURL = $this->uri . 'send-sms-code';
  }

  /** @test */
  public function send_sms_ok(): void
  {
    $this
      -> post(
          $this->sendSMSURL,
          [
            'type' => AppFormType::REGISTER,
            'phone_number' => '+995591935080'
          ]
        )
      -> ok();
  }

  /** @test */
  public function send_sms_has_validation_errors(): void
  {
    $this
      -> post(
        $this->sendSMSURL,
        [],
        ['Accept' => 'application/json'],
      )
      ->assertJsonValidationErrors(['type', 'phone_number'], 'error');
      
  }
}