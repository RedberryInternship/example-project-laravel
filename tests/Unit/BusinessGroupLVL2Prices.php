<?php

namespace Test\Unit;

use App\Enums\ConnectorType as EnumsConnectorType;
use Illuminate\Support\Collection;
use App\ChargerConnectorType;
use App\ConnectorType;
use Tests\TestCase;
use App\Charger;
use App\Company;
use App\Group;
use App\User;

class BusinessGroupLVL2Prices extends TestCase
{
  /**
   * Route URLs.
   */
  private function groupLVL2PricesURL($id) { return "business/group-prices/$id"; }

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
   * Business groups.
   * 
   * @var Collection
   */
  private $groups;

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

    $this->createConnectorTypes();
    $this->company = factory(Company::class)->create();
    $this->user = factory(User::class)->create(
      [
        'role_id' => $this->createBusinessRole()->id,
        'company_id' => $this->company->id,
      ]
    );

    $this->groups = factory(Group::class, 5)->create(['user_id' => $this->user->id]);
    $this->chargers = factory(Charger::class, 20)
      ->create(['company_id' => $this->company->id])
      ->each(function($charger) {
        $this->groups->random()->chargers()->attach($charger);
      });
    
    $this
      ->chargers
      ->each(function($charger) {
        factory(ChargerConnectorType::class)
        ->create(
          [
            'charger_id' => $charger->id,
            'connector_type_id' => ConnectorType::whereName(EnumsConnectorType::TYPE_2)->first()->id,
            'min_price' => null,
            'max_price' => null,
          ],
        );
      }
    );
  }

  /** @test */
  public function show_group_with_lvl_2_prices_is_ok()
  {
    $group = $this->groups->random();

    $this
      ->actingAs($this->user)
      ->get($this->groupLVL2PricesURL($group->id))
      ->assertOk();
  }
  
  /** @test */
  public function show_group_with_lvl_2_prices_not_found()
  {
    $groupId = 1232112;

    $this
      ->actingAs($this->user)
      ->get($this->groupLVL2PricesURL($groupId))
      ->assertNotFound();
  }
    
  /** @test */
  public function create_lvl_2_charging_price_for_group_is_ok(): void
  {
    $brandNewGroup = factory(Group::class)->create([ 'user_id' => $this->user->id ]);
    $this->chargers->each(function(Charger $charger) use(&$brandNewGroup) {
      $brandNewGroup->chargers()->attach($charger);
    });

    $startTime = '00:00';
    $endTime = '00:00';
    $minKWT = 0;
    $maxKWT = 10;
    $price = 1;

    $this
      ->actingAs($this->user)
      ->put(
        $this->groupLVL2PricesURL($brandNewGroup->id),
        [
          'start_time' => $startTime,
          'end_time'   => $endTime,
          'min_kwt'    => $minKWT,
          'max_kwt'    => $maxKWT,
          'price'      => $price,
        ],
      )
      ->assertSessionHasNoErrors();
  }
    
  /** @test */
  public function create_lvl_2_charging_price_for_group_has_validations_errors(): void
  {
    $brandNewGroup = factory(Group::class)->create([ 'user_id' => $this->user->id ]);
    $this->chargers->each(function(Charger $charger) use(&$brandNewGroup){
      $brandNewGroup->chargers()->attach($charger);
    });

    $this
      ->actingAs($this->user)
      ->put(
        $this->groupLVL2PricesURL($brandNewGroup->id),
        [
          // 'start_time' => $startTime,
          // 'end_time'   => $endTime,
          // 'min_kwt'    => $minKWT,
          // 'max_kwt'    => $maxKWT,
          // 'price'      => $price,
        ],
      )
      ->assertSessionHasErrors(['start_time', 'end_time', 'max_kwt', 'min_kwt', 'price']);
  }
}