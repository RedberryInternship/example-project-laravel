<?php

namespace Test\Unit;

use App\Enums\Role as EnumsRole;
use Tests\TestCase;
use App\Charger;
use App\Company;
use App\Group;
use App\Role;
use App\User;

class BusinessChargerTransfer extends TestCase
{
  /**
   * Routes.
   */
  private $transferChargerURL = 'business/charger-transfer';

  /**
   * Business user.
   * 
   * @var User
   */
  private $user;

  /**
   * Business group.
   * 
   * @var Group
   */
  private $group;

  /**
   * Business charger.
   * 
   * @var Charger
   */
  private $charger;

  /**
   * Business company.
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

    $role = factory(Role::class)->create([ 'name' => EnumsRole::BUSINESS ]);
    $this -> company = factory(Company::class)->create();

    $this->user = factory(User::class)->create(
      [
        'role_id' => $role->id,
        'company_id' => $this -> company -> id,
      ],
    );

    $this->group = factory(Group::class)->create(
      [
        'user_id' => $this -> user -> id,
        'name' => 'Samantha',
      ],
    );

    $this->charger = factory(Charger::class)->create(
      [
        'company_id' => $this -> company -> id,
      ]
    );
  }

  /** @test */
  public function transfer_charger_into_the_group_is_ok(): void
  {
    $this
      -> actingAs($this->user)
      -> post(
        $this->transferChargerURL,
        [
          'group-id' => $this -> group -> id,
          'charger-id' => $this -> charger -> id,
        ]
      )
      -> assertSessionHasNoErrors();

    $this -> group -> refresh();
    $this -> charger -> refresh();

    $this -> assertEquals(
      $this->charger -> id, 
      $this -> group -> chargers -> first() -> id,
    );
  }

  /** @test */
  public function transfer_charger_has_validation_errors(): void
  {
    $this
      -> actingAs($this->user)
      -> post(
        $this->transferChargerURL,
        [
          // 'group-id' => $this -> group -> id,
          // 'charger-id' => $this -> charger -> id,
        ]
      )
      -> assertSessionHasErrors(['group-id', 'charger-id']);
  }

  /** @test */
  public function transfer_charger_into_the_group_is_forbidden(): void
  {
    $intruderCharger = factory(Charger::class)->create();
    
    $this
      -> actingAs($this->user)
      -> post(
        $this->transferChargerURL,
        [
          'group-id' => $this -> group -> id,
          'charger-id' => $intruderCharger -> id,
        ]
      )
      -> assertForbidden();
  }
  
  /** @test */
  public function remove_charger_from_group_is_ok(): void
  {
    $this->group->chargers()->attach($this->charger);
    
    $this
      -> actingAs($this->user)
      -> post(
        $this->transferChargerURL,
        [
          'group-id' => $this -> group -> id,
          'charger-id' => $this -> charger -> id,
          'remove' => true,
        ]
      )
      -> assertSessionHasNoErrors();
    
    $this -> group -> refresh();
    $this -> assertEmpty($this -> group -> chargers);
  }
}