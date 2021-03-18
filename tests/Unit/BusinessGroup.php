<?php

namespace Test\Unit;

use Illuminate\Support\Collection;
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
  private function updateGroupsURL($id) { return "business/groups/$id/update"; }
  private function destroyGroupsURL($id) { return "business/groups/$id"; }
  private $deleteAllPricesURL = 'business/groups/charging-prices/delete';
  private $storeChargingPricesURL = 'business/groups/store/all';
  private function showGroupLVL2PricesURL($id) { return "business/group-prices/$id"; }
  private function updateGroupLVL2PricesURL($id) { return "business/group-prices/$id"; } 
  private function showGroupFASTPricesURL($id) { return "business/group-fast-prices/$id"; }
  private function updateGroupFASTPricesURL($id) { return "business/group-fast-prices/$id"; } 

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

}