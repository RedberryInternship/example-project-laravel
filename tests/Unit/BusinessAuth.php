<?php

namespace Test\Unit;

use App\Enums\Role as EnumsRole;
use Tests\TestCase;
use App\Company;
use App\Role;

class BusinessAuth extends TestCase
{
  /**
   * API routes.
   */
  private $authURL = 'business/auth';
  private $loginURL = 'business/login';
  private $logoutURL = 'business/logout';
  private $dashboardURL = 'business';

  /**
   * Credentials.
   */
  private $businessUserEmail = 'bejan@mamasaxlisi.ge';
  private $businessUserPassword = 'atasiarmia';
  private $adminUserEmail = 'bejan@myvideo.ge';
  private $adminUserPassword = 'saitebi.ge';

  /**
   * Dummy users.
   */
  private $adminUser;
  private $businessUser;

  /**
   * Company.
   * 
   * @var Company
   */
  private $company;

  /**
   * Set up..
   */
  protected function setUp(): void
  {
    parent::setUp();  

    $this -> company = factory(Company::class)->create();

    factory(Role::class) -> create([ 'name' => EnumsRole::ADMIN ]);
    factory(Role::class) -> create([ 'name' => EnumsRole::BUSINESS ]);

    $this -> businessUser = $this -> createUser(
      [
        'role_id' => Role::whereName(EnumsRole::BUSINESS)->first()->id,
        'email' => $this -> businessUserEmail,
        'password' => bcrypt($this -> businessUserPassword),
        'company_id' => $this -> company -> id,
      ]
    );
    
    $this -> adminUser = $this -> createUser(
      [
        'role_id' => Role::whereName(EnumsRole::ADMIN)->first()->id,
        'email' => $this -> adminUserEmail,
        'password' => bcrypt($this -> adminUserPassword),
        'company_id' => $this -> company -> id,
      ]
    );
  }

  /** @test */
  public function get_login_ok(): void
  {
    $this 
      -> get($this -> loginURL)
      -> assertOk();
  }

  /** @test */
  public function auth_ok(): void
  {
    $this
      -> post(
        $this -> authURL,
        [
          'email' => $this -> businessUserEmail,
          'password' => $this -> businessUserPassword,
        ]
      )
      ->assertRedirect('/business');
  }
  
  /** @test */
  public function auth_validation_has_errors(): void
  {
    $this
      -> post(
        $this -> authURL,
        [
          // 'email' => $this -> email,
          'password' => $this -> businessUserPassword,
        ]
      )
      -> assertSessionHasErrors(['email']);
  }
  
  /** @test */
  public function auth_validation_has_auth_errors(): void
  {
    $this
      -> post(
        $this -> authURL,
        [
          'email' => $this -> businessUserEmail,
          'password' => 'non-existing-password',
        ]
      )
      -> assertSessionHasErrors();
  }

  /** @test */
  public function let_authorization_for_only_business_users(): void
  {
    $this
      -> post(
        $this -> authURL,
        [
          'email' => $this -> adminUserEmail,
          'password' => $this -> adminUserPassword,
        ]
      )
      -> assertSessionHasErrors();
  }

  /** @test */
  public function logout_ok(): void
  {
    $this
      -> actAs($this -> businessUser)
      -> get($this -> logoutURL)
      -> assertRedirect('/business/login');
  }

  /** @test */
  public function get_dashboard_ok(): void
  {
    $this
      -> actingAs($this->businessUser)
      -> get($this->dashboardURL)
      -> assertOk();
  }
}