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

class BusinessGroupFastPrices extends TestCase
{
  /**
   * Route URLs.
   */
  private function groupFASTPricesURL($id) { return "business/group-fast-prices/$id"; }

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
        return $this->groups->random()->chargers()->attach($charger);
       });
    
    $this
      ->chargers
      ->each(function($charger) {
        factory(ChargerConnectorType::class)
          ->create(
          [
            'charger_id' => $charger->id,
            'connector_type_id' => ConnectorType::whereName(EnumsConnectorType::COMBO_2)->first()->id,
          ],
        );
      }
    );
  }

  /** @test */
  public function show_group_with_fast_prices_is_ok()
  {
    $group = $this->groups->random();

    $this
      ->actingAs($this->user)
      ->get($this->groupFASTPricesURL($group->id))
      ->assertOk();
  }
  
  /** @test */
  public function show_group_with_fast_prices_not_found()
  {
    $groupId = 1232112;

    $this
      ->actingAs($this->user)
      ->get($this->groupFASTPricesURL($groupId))
      ->assertNotFound();
  }
    
  /** @test */
  public function create_fast_charging_price_for_group_is_ok(): void
  {
    $brandNewGroup = factory(Group::class)->create([ 'user_id' => $this->user->id ]);
    $this->chargers->each(function(Charger $charger) use(&$brandNewGroup) {
      $brandNewGroup->chargers()->attach($charger);
    });

    $startMinutes = 0;
    $endMinutes = 10;
    $price = 1;

    $this
      ->actingAs($this->user)
      ->put(
        $this->groupFASTPricesURL($brandNewGroup->id),
        [
          'start_minutes' => $startMinutes,
          'end_minutes'   => $endMinutes,
          'price'         => $price,
        ],
      )
      ->assertSessionHasNoErrors();
  }
    
  /** @test */
  public function create_lvl_2_charging_price_for_group_has_validation_errors(): void
  {
    $brandNewGroup = factory(Group::class)->create([ 'user_id' => $this->user->id ]);
    $this->chargers->each(function(Charger $charger) use(&$brandNewGroup) {
      $brandNewGroup->chargers()->attach($charger);
    });

    $this
      ->actingAs($this->user)
      ->put(
        $this->groupFASTPricesURL($brandNewGroup->id),
        [
          // 'start_minutes' => $startMinutes,
          // 'end_minutes'   => $endMinutes,
          // 'price'         => $price,
        ],
      )
      ->assertSessionHasErrors(['start_minutes', 'end_minutes', 'price']);
  }
}