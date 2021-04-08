<?php

namespace Test\Unit;

use Tests\TestCase;
use App\Enums\AppFormType;
use App\Library\Testing\SMS;
use App\TempSmsCode;

class Verify extends TestCase
{
  /**
   * Replace Sms provider with mocker.
   */
  protected function setUp(): void
  {
    parent :: setUp();

    $this -> app -> bind( 'SMSProvider', SMS :: class );
    $this -> sendSMSURL = $this -> uri . 'send-sms-code';
    $this -> verifyCodeURL = $this -> uri . 'verify-code';
    $this -> verifyCodeForPasswordRecoveryURL = $this -> uri . 'verify-code-for-password-recovery';
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
      -> assertOk();
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

  /** @test */
  public function verify_code_is_ok(): void
  {
    $user = $this->createUser();
    TempSmsCode::create(
      [
        'phone_number' => $user->phone_number,
        'code' => '0028',
      ]
    );

    $this
      -> post(
        $this -> verifyCodeURL,
        [
          'phone_number' => $user->phone_number,
          'code' => '0028',
        ]
      )
      ->assertOk();
  }
  
  /** @test */
  public function verify_code_has_validation_errors(): void
  {
    $user = $this->createUser();
    TempSmsCode::create(
      [
        'phone_number' => $user->phone_number,
        'code' => '0028',
      ]
    );

    $this
      -> post(
        $this -> verifyCodeURL,
        [
          // 'phone_number' => $user->phone_number,
          'code' => '0028',
        ],
        ['Accept' => 'application/json'],
      )
      ->assertJsonValidationErrors(['phone_number'], 'error');
  }

  /** @test */
  public function verify_code_for_password_recovery_is_ok(): void
  {
    $user = $this->createUser();
    TempSmsCode::create(
      [
        'phone_number' => $user->phone_number,
        'code' => '0000',
      ]
    );
    
    $this
      -> post(
        $this -> verifyCodeForPasswordRecoveryURL,
        [
          'phone_number' => $user->phone_number,
          'code' => '0000',
        ],
      )->assertOk();
  }

  /** @test */
  public function verify_code_for_password_recovery_has_validation_errors(): void
  {
    $user = $this->createUser();
    TempSmsCode::create(
      [
        'phone_number' => $user->phone_number,
        'code' => '0000',
      ]
    );
    
    $this
      -> post(
        $this -> verifyCodeForPasswordRecoveryURL,
        [
          // 'phone_number' => $user->phone_number,
          // 'code' => '0000',
        ],
      )->assertJsonValidationErrors(['phone_number', 'code'], 'error');
  }
}