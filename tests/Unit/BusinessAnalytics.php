<?php

namespace Test\Unit;

use App\Enums\ConnectorType as EnumsConnectorType;
use Illuminate\Support\Collection;
use App\ChargerConnectorType;
use App\Enums\ChargerStatus;
use App\Enums\OrderStatus;
use App\Enums\PaymentType;
use App\ConnectorType;
use Tests\TestCase;
use App\Charger;
use App\Company;
use App\Payment;
use App\Order;
use App\User;

class BusinessAnalytics extends TestCase
{
  /**
   * Route URLs.
   */
  private $incomeURL = 'business/analytics/income';
  private $transactionsURL = 'business/analytics/transactions';
  private $topChargersURL = 'business/analytics/top-chargers';
  private $chargerStatusesURL = 'business/analytics/charger-statuses';

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
   * Business transactions.
   * 
   * @var Collection
   */
  private $transactions;

  /**
   * Fast connector type.
   * 
   * @var ConnectorType
   */
  private $fastConnectorType;

  /**
  * LVL 2 connector type.
  *
  * @var ConnectorType
  */
  private $lvl2ConnectorType;

  /**
   * Set up...
   */
  protected function setUp(): void
  {
    parent::setUp();

    $this->createConnectorTypes();
    $this->company = factory(Company::class)->create();
    $this->user = $this->createUser(
      [
        'company_id' => $this->company->id,
        'role_id' => $this->createBusinessRole()->id,
      ]
    );

    $this->lvl2ConnectorType = ConnectorType::whereName(EnumsConnectorType::TYPE_2)->first();
    $this->fastConnectorType = ConnectorType::whereName(EnumsConnectorType::CHADEMO)->first();
  }

  /** @test */
  public function income_is_ok(): void
  {
    $this
      ->actingAs($this->user)
      ->get($this->incomeURL)
      ->assertOk();
  }

  /** @test */
  public function income_penalty_calculations_is_right(): void
  {
    $penaltyFee = 10;
    $penaltyOrderNumber = 12;

    factory(Order::class, $penaltyOrderNumber)->create(
      [
        'penalty_fee' => $penaltyFee,
        'company_id' => $this -> company -> id,
        'user_id' => $this->user->id,
        'charging_status' => OrderStatus::FINISHED,
      ]
    );

    $result = $this
      ->actingAs($this->user)
      ->get($this->incomeURL)
      ->decodeResponseJson('penalty');

    $currentMonth = now()->month - 1;

    $calculatedPenaltyFee = $result[$currentMonth];

    $this->assertTrue($calculatedPenaltyFee === $penaltyFee * $penaltyOrderNumber);
  }
  
  /** @test */
  public function income_calculations_is_right(): void
  {
    $chargePrice = 12;
    $numberOfOrders = 100;

    factory(Order::class, $numberOfOrders)->create(
      [
        'penalty_fee' => 20,
        'charge_price' => $chargePrice,
        'company_id' => $this -> company -> id,
        'user_id' => $this->user->id,
        'charging_status' => OrderStatus::FINISHED,
      ]
    );

    $result = $this
      ->actingAs($this->user)
      ->get($this->incomeURL)
      ->decodeResponseJson('income_without_penalty');

    $currentMonth = now()->month - 1;

    $calculatedIncome = $result[$currentMonth];

    $this->assertTrue($calculatedIncome === $chargePrice * $numberOfOrders);
  }

  /** @test */
  public function transactions_and_wasted_energy_data_is_ok(): void
  {
    $this
      ->actingAs($this->user)
      ->get($this->transactionsURL)
      ->assertOk();
  }
  
  /** @test */
  public function transactions_and_wasted_energy_calculations_are_right(): void
  {
    $numberOfOrders = 10;
    $usedWatts = 12;

    factory(Order::class, $numberOfOrders)->create(
      [
        'penalty_fee' => 0,
        'company_id' => $this -> company -> id,
        'user_id' => $this->user->id,
        'charging_status' => OrderStatus::FINISHED,
        'consumed_kilowatts' => $usedWatts,
      ]
    )->each(fn($order) => factory(Payment::class)
        ->create(
          [
            'type' => PaymentType::CUT,
            'order_id' => $order->id,
          ]
        )
      );

    $data = $this
      ->actingAs($this->user)
      ->get($this->transactionsURL)
      ->decodeResponseJson();

    $usedKilowatts = $usedWatts * 0.001;
    $allTheUsedKilowatts = $usedKilowatts * $numberOfOrders;

    $currentMonth = now()->month - 1;
    $calculatedEnergy = $data['energy'][$currentMonth];
    $calculatedNumberOfOrders = $data['transactions'][$currentMonth];
    
    $this->assertTrue($allTheUsedKilowatts === $calculatedEnergy);
    $this->assertTrue($numberOfOrders === $calculatedNumberOfOrders);
  }

  /** @test */
  public function top_chargers_is_ok(): void
  {
    $this
      ->actingAs($this->user)
      ->get($this->topChargersURL)
      ->assertOk();
  }
  
  /** @test */
  public function top_chargers_has_right_structure(): void
  {
    $this
      ->actingAs($this->user)
      ->get($this->topChargersURL)
      ->assertJsonStructure(
        [
          'top_by_number_of_orders',
          'top_by_kilowatts',
          'top_by_duration',
        ]
      );
  }

  /** @test */
  public function charger_statuses_is_ok(): void
  {
    $this
      ->actingAs($this->user)
      ->get($this->chargerStatusesURL)
      ->assertOk();
  }
  
  /** @test */
  public function charger_statuses_has_right_structure(): void
  {
    $this
      ->actingAs($this->user)
      ->get($this->chargerStatusesURL)
      ->assertJsonStructure(
        [
          'lvl2',
          'fast',
          'labels',
          'statuses',
        ]
      );
  }

  /** @test */
  public function charger_statuses_has_right_info(): void
  {
    $fastFree = 5;
    $fastCharging = 12;
    $fastNotWorking = 33;
    
    $lvl2Free = 3;
    $lvl2Charging = 2;
    $lvl2NotWorking = 143;

    /**
     * 5 fast free charger.
     */
    factory(Charger::class, $fastFree)->create(
      [
        'status' => ChargerStatus::ACTIVE,
        'company_id' => $this->company->id,
      ],
    )->each(fn($charger) => factory(ChargerConnectorType::class)->create(
        [
          'charger_id' => $charger->id,
          'connector_type_id' => $this->fastConnectorType->id,
        ],
      )
    );
    
    /**
     * 12 fast charger currently charging.
     */
    factory(Charger::class, $fastCharging)->create(
      [
        'status' => ChargerStatus::CHARGING,
        'company_id' => $this->company->id,
      ],
    )->each(fn($charger) => factory(ChargerConnectorType::class)->create(
        [
          'charger_id' => $charger->id,
          'connector_type_id' => $this->fastConnectorType->id,
        ],
      )
    );
    
    /**
     * 33 fast charger currently not working.
     */
    factory(Charger::class, $fastNotWorking)->create(
      [
        'status' => ChargerStatus::INACTIVE,
        'company_id' => $this->company->id,
      ],
    )->each(fn($charger) => factory(ChargerConnectorType::class)->create(
        [
          'charger_id' => $charger->id,
          'connector_type_id' => $this->fastConnectorType->id,
        ],
      )
    );
    
    /**
     * 3 lvl2 charger currently free.
     */
    factory(Charger::class, $lvl2Free)->create(
      [
        'status' => ChargerStatus::ACTIVE,
        'company_id' => $this->company->id,
      ],
    )->each(fn($charger) => factory(ChargerConnectorType::class)->create(
        [
          'charger_id' => $charger->id,
          'connector_type_id' => $this->lvl2ConnectorType->id,
        ],
      )
    );
    
    /**
     * 2 lvl2 charger currently charging.
     */
    factory(Charger::class, $lvl2Charging)->create(
      [
        'status' => ChargerStatus::CHARGING,
        'company_id' => $this->company->id,
      ],
    )->each(fn($charger) => factory(ChargerConnectorType::class)->create(
        [
          'charger_id' => $charger->id,
          'connector_type_id' => $this->lvl2ConnectorType->id,
        ],
      )
    );
    
    /**
     * 143 lvl2 charger currently not working.
     */
    factory(Charger::class, $lvl2NotWorking)->create(
      [
        'status' => ChargerStatus::INACTIVE,
        'company_id' => $this->company->id,
      ],
    )->each(fn($charger) => factory(ChargerConnectorType::class)->create(
        [
          'charger_id' => $charger->id,
          'connector_type_id' => $this->lvl2ConnectorType->id,
        ],
      )
    );


    $data = $this
      ->actingAs($this->user)
      ->get($this->chargerStatusesURL)
      ->decodeResponseJson();

    $this->assertTrue($fastFree === $data['fast'][0]);
    $this->assertTrue($fastCharging === $data['fast'][1]);
    $this->assertTrue($fastNotWorking === $data['fast'][2]);

    $this->assertTrue($lvl2Free === $data['lvl2'][0]);
    $this->assertTrue($lvl2Charging === $data['lvl2'][1]);
    $this->assertTrue($lvl2NotWorking === $data['lvl2'][2]);
  }
}
