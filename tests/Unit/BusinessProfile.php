<?php

namespace Test\Unit;

use App\Company;
use Tests\TestCase;
use App\Role;
use App\Enums\Role as RoleEnum;

class BusinessProfile extends TestCase
{
  /**
   * Api routes.
   */
  private $profileURL = 'business/profile';
  private $downloadContract = 'business/profile/download-contract';

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

    factory(Role::class) -> create([ 'name' => RoleEnum::BUSINESS ]);
    
    $this -> company = factory(Company::class)->create();

    $this -> user = $this -> createUser(
      [
        'role_id' => Role::whereName(RoleEnum::BUSINESS)->first()->id,
        'email' => 'bejan@myvideo.ge',
        'password' => bcrypt('suliwminda'),
        'company_id' => $this -> company -> id,
      ]
    );
  }

  /** @test */
  public function get_profile_view_ok(): void
  {
    $this
      -> actingAs($this->user)
      -> get($this->profileURL)
      -> assertOk();
  }
  
  /** @test */
  public function update_business_profile_info_ok(): void
  {
    $name = 'გელა';
    $phone = '+995591935088';
    $email = 'bearbowl@gotverani.ge';

    $this
      -> actingAs($this->user)
      -> post(
        $this->profileURL,
        [
          'first_name'   => $name,
          'phone_number' => $phone,
          'email' => $email,
        ]
      )
      -> assertSessionHasNoErrors();
  }
  
  /** @test */
  public function update_business_profile_info_has_validation_errors(): void
  {
    $name = 'გელა';
    $phone = '+995591935088';
    $email = 'bearbowl@gotverani.ge';

    $this
      -> actingAs($this->user)
      -> post(
        $this->profileURL,
        [
          // 'first_name'   => $name,
          // 'phone_number' => $phone,
          // 'email' => $email,
        ]
      )
      -> assertSessionHasErrors(['first_name', 'phone_number', 'email']);
  }

  /** @test */
  public function download_contract_file_is_ok(): void
  {
    /**
     * Create dummy contract file.
     */
    $fileName = 'contract.pdf';
    $content = 'contract content';
    $path =  '/storage/app/public' . $fileName;
    $fullPath = base_path($path);

    file_put_contents($fullPath, $content);


    
    /**
     * Remove contract file.
     */
    unlink($fullPath);
  }
}