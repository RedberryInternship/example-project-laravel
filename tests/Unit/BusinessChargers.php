<?php

namespace Test\Unit;

use Illuminate\Support\Collection;
use App\Enums\Role as RoleEnum;
use Tests\TestCase;
use App\Charger;
use App\Company;
use App\Role;
use App\User;

class BusinessChargers extends TestCase
{
  /**
   * Routes.
   */
  private $allChargersURL = 'business/chargers';
  private function editChargerURL($id) {return "business/chargers/$id/edit"; }
  private function updateChargerURL($id) { return "business/chargers/$id/update"; }
  private $filteredChargerURL = 'business/filter-chargers';

  /**
   * Business chargers.
   * 
   * @var Collection
   */
  private $chargers;

  /**
   * Business user.
   * 
   * @var User
   */
  private $user;

  /**
   * Business company.
   * 
   * @var Company
   */
  private $company;

  /**
   * Set up...
   */
  protected function setUp(): void
  {
    parent::setUp();

    $role = factory(Role::class) -> create([ 'name' => RoleEnum::BUSINESS]);
    $this -> company = factory( Company::class ) -> create();
    $this -> user = $this -> createUser(
      [ 
        'company_id' => $this -> company -> id,
        'role_id' => $role -> id,
      ]
    );

    $this -> chargers = factory( Charger::class, 10 ) -> create([ 'company_id' => $this -> company -> id ]);
  }

  /** @test */
  public function get_all_chargers_is_ok(): void
  {
    $this
      -> actingAs($this->user)
      -> get($this->allChargersURL)
      -> assertOk();
  }

  /** @test */
  public function edit_charger_is_ok(): void
  {
    $chargerId = $this -> chargers -> first() -> id;

    $this
      -> actingAs($this->user)
      -> get($this->editChargerURL($chargerId))
      -> assertOk();
  }
  
  /** @test */
  public function edit_charger_is_not_found(): void
  {
    $chargerId = 10001;

    $this
      -> actingAs($this->user)
      -> get($this->editChargerURL($chargerId))
      -> assertNotFound();
  }

  /** @test */
  public function update_charger_is_ok(): void
  {
    $charger = $this->chargers->first();
    $name = 'WV Wilson';

    $this
      -> actingAs($this->user)
      -> post(
        $this->updateChargerURL($charger->id),
        [
          'names' => [
            'en' => $name,
            'ka' => $name,
            'ru' => $name,
          ],
        ])
        ->assertSessionHasNoErrors();
  }

  /** @test */
  public function get_filtered_chargers_is_ok(): void
  {
    $this
      ->actingAs($this->user)
      ->post($this->filteredChargerURL)
      ->assertOk();
  }
}