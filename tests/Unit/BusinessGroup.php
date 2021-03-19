<?php

namespace Test\Unit;

use Illuminate\Support\Collection;
use App\ChargerConnectorType;
use App\FastChargingPrice;
use App\ChargingPrice;
use Tests\TestCase;
use App\Company;
use App\Charger;
use App\Group;
use App\User;

class BusinessGroup extends TestCase
{
  /**
   * Routes.
   */
  private $groupsURL = 'business/groups';
  private function editGroupsURL($id) { return "business/groups/$id/edit"; }
  private function destroyGroupsURL($id) { return "business/groups/$id"; }
  private $deleteAllGroupPricesURL = 'business/groups/charging-prices/delete';
  private $storeAllChargersIntoGroupURL = 'business/groups/store/all'; 

  /**
   * Business user.
   * 
   * @var User
   */
  private $user;

  /**
   * Business groups.
   * 
   * @var Collection
   */
  private $groups;

  /**
   * Business company.
   * 
   * @var Company
   */
  private $company;

  /**
   * Business chargers.
   * 
   * @var Collection
   */
  private $chargers;

  /**
   * Set up...
   */
  protected function setUp(): void
  {
    parent::setUp();

    $this->company = factory(Company::class)->create();
    $this->user = $this->createUser(
      [ 
        'company_id' => $this->company,
        'role_id' => $this->createBusinessRole()->id,
      ],
    );

    $this->groups = factory(Group::class, 10)->create(['user_id' => $this->user->id]);
    $this->chargers = factory(Charger::class, 20)
      ->create([ 'company_id' => $this->company->id ])
      ->each(function($charger) {
        $charger->groups()->attach($this->groups->random());
        $chargerConnectorType = factory(ChargerConnectorType::class)
          ->create(['charger_id' => $charger->id]);
        

        $chargingPriceModel = $charger->id % 2 === 0 
          ? ChargingPrice::class 
          : FastChargingPrice::class;

        factory($chargingPriceModel, 3) -> create(['charger_connector_type_id' => $chargerConnectorType->id]);
      });
  }

  /** @test */
  public function get_groups_is_ok(): void
  {
    $this
      ->actingAs($this->user)
      ->get($this->groupsURL)
      ->assertOk();
  }

  /** @test */
  public function store_group_is_ok(): void
  {
    $groupName = 'NAmakhvan';

    $this
      ->actingAs($this->user)
      ->post(
        $this->groupsURL,
        [
          'name' => $groupName,
        ],
      )
      ->assertSessionHasNoErrors();

    $createdGroup = Group::whereName($groupName)->first();

    $this->assertNotNull($createdGroup);
  }
  
  /** @test */
  public function store_group_has_validation_errors(): void
  {
    $this
      ->actingAs($this->user)
      ->post(
        $this->groupsURL,
        [
          // 'name' => 'NAmakhvan',
        ],
      )
      ->assertSessionHasErrors(['name']);
  }

  /** @test */
  public function edit_group_is_ok(): void
  {
    $group = $this->groups->random();

    $this
      ->actingAs($this->user)
      ->get($this->editGroupsURL($group->id))
      ->assertOk();
  }

  /** @test */
  public function edit_group_is_not_found(): void
  {
    $this
      ->actingAs($this->user)
      ->get($this->editGroupsURL(10001))
      ->assertNotFound();
  }

  /** @test */
  public function destroy_group_is_ok(): void
  {
    $this->groups->each(fn(Group &$group) => $group->load('chargers'));

    $group = $this
      ->groups
      ->filter(fn(Group $group) => $group->chargers->count() > 0)
      ->first();
    
    $this
      ->actingAs($this->user)
      ->delete($this->destroyGroupsURL($group->id))
      ->assertStatus(201);

    $fetchedGroup = Group::find($group->id);

    $this->assertNull($fetchedGroup);
  }

  /** @test */
  public function store_group_all_chargers_into_the_group_is_ok(): void
  {
    $group = $this->groups->random();

    $this
      ->actingAs($this->user)
      ->post(
        $this->storeAllChargersIntoGroupURL,
        [
          'group_id' => $group->id,
        ],
      )
      ->assertOk();

    $group->refresh();
    
    $this->assertCount($this->chargers->count(), $group->chargers);
  }

  /** @test */
  public function store_group_all_chargers_into_the_group_is_not_found(): void
  {
    $groupId = 100001;

    $this
      ->actingAs($this->user)
      ->post(
        $this->storeAllChargersIntoGroupURL,
        [
          'group_id' => $groupId,
        ],
      )
      ->assertNotFound();
  }

  /** @test */
  public function delete_all_charger_prices_in_the_group(): void
  {
    $this->groups->each(fn(Group &$group) => $group->load('chargers'));

    $group = $this
      ->groups
      ->filter(fn(Group $group) => $group->chargers->count() > 0)
      ->first();
    
    $this
      ->actingAs($this->user)
      ->delete(
        $this->deleteAllGroupPricesURL,
        [
          'group_id' => $group->id,
        ],
      )
      ->assertOk();

    $group->refresh();

    $chargingPricesCount = 0;
    $group->chargers->each(function($charger) use($chargingPricesCount) {
      $charger->load(
        [
          'charger_connector_types.fast_charging_prices',
          'charger_connector_types.charging_prices',
        ]
      );

      $charger->charger_connector_types->each(fn($cct) => $chargingPricesCount += $cct->charging_prices->count());
      $charger->charger_connector_types->each(fn($cct) => $chargingPricesCount += $cct->fast_charging_prices->count());
    });

    $this->assertTrue($chargingPricesCount === 0);
  }

  /** @test */
  public function delete_all_charger_prices_in_the_group_returns_not_found(): void
  {
    $groupId = 12312124;

    $this
      ->actingAs($this->user)
      ->delete(
        $this->deleteAllGroupPricesURL,
        [
          'group_id' => $groupId,
        ],
      )
      ->assertNotFound();
  }

  /** @test */
  public function delete_all_charger_prices_in_the_group_has_validation_errors(): void
  {
    $this
      ->actingAs($this->user)
      ->delete(
        $this->deleteAllGroupPricesURL,
        [
          // 'group_id' => $groupId,
        ],
      )
      ->assertSessionHasErrors(['group_id']);
  }
}