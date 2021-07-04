<?php

namespace Test\Unit;

use App\Enums\ConnectorType as EnumsConnectorType;
use App\ChargerConnectorType;
use App\ChargingPrice;
use App\ConnectorType;
use Tests\TestCase;
use App\Company;
use App\Charger;
use App\User;

class BusinessLVL2ChargingPrices extends TestCase
{
  /** 
   * Route URLs.
   */ 
  private $storeLVL2ChargingPriceURL = 'business/charging-prices';
  private function destroyLVL2ChargingPrice($id) { return "business/charging-prices/$id"; }

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
   * Business charger.
   * 
   * @var Charger
   */
  private $charger;

  /**
   * Business charger chargerConnectorType.
   * 
   * @var ChargerConnectorType
   */
  private $chargerConnectorType;

  /**
   * Set up...
   */
  protected function setUp(): void
  {
    parent::setUp();

    $this->createConnectorTypes();
    $this->company = factory(Company::class)->create();
    $this->user = $this -> createUser(
      [
        'role_id' => $this->createBusinessRole()->id,
        'company_id' => $this->company->id,
      ],
    );

    $this->charger = factory(Charger::class)->create(['company_id' => $this->company->id]);
    $this->chargerConnectorType = factory(ChargerConnectorType::class)->create(
      [
        'charger_id' => $this->charger->id,
        'connector_type_id' => ConnectorType::whereName(EnumsConnectorType::TYPE_2)->first()->id,
      ]
    );
  }

  /** @test */
  public function store_lvl_2_charging_price_is_ok(): void
  {
    $startTime = '00:00';
    $endTime = '00:00';
    $minKWT = 0;
    $maxKWT = 10;
    $price = 1;

    $this
      ->actingAs($this->user)
      ->post(
        $this->storeLVL2ChargingPriceURL,
        [
          'charger_connector_type_id' => $this->chargerConnectorType->id,
          'start_time' => $startTime,
          'end_time'   => $endTime,
          'min_kwt'    => $minKWT,
          'max_kwt'    => $maxKWT,
          'price'      => $price,
        ]
      )
      ->assertStatus(201);

    $this->charger->refresh();

    $this->chargerConnectorType->refresh();
    $lvl2ChargingPricesCount = $this->chargerConnectorType->charging_prices->count();

    $this->assertTrue($lvl2ChargingPricesCount === 1);
  }
  
  /** @test */
  public function store_lvl_2_charging_price_is_not_found(): void
  {
    $startTime = '00:00';
    $endTime = '00:00';
    $minKWT = 0;
    $maxKWT = 10;
    $price = 1;

    $this
      ->actingAs($this->user)
      ->post(
        $this->storeLVL2ChargingPriceURL,
        [
          'charger_connector_type_id' => 10001,
          'start_time' => $startTime,
          'end_time'   => $endTime,
          'min_kwt'    => $minKWT,
          'max_kwt'    => $maxKWT,
          'price'      => $price,
        ]
      )
      ->assertNotFound();
  }
  
  /** @test */
  public function store_lvl_2_charging_price_is_forbidden(): void
  {
    $startTime = '00:00';
    $endTime = '00:00';
    $minKWT = 0;
    $maxKWT = 10;
    $price = 1;

    $intruderChargerConnectorType = factory(ChargerConnectorType::class)->create();

    $this
      ->actingAs($this->user)
      ->post(
        $this->storeLVL2ChargingPriceURL,
        [
          'charger_connector_type_id' => $intruderChargerConnectorType->id,
          'start_time' => $startTime,
          'end_time'   => $endTime,
          'min_kwt'    => $minKWT,
          'max_kwt'    => $maxKWT,
          'price'      => $price,
        ]
      )
      ->assertForbidden();
  }
 
  /** @test */
  public function store_lvl_2_charging_price_has_validation_errors(): void
  {
    $this
      ->actingAs($this->user)
      ->post(
        $this->storeLVL2ChargingPriceURL,
        [
          // 'charger_connector_type_id' => $intruderChargerConnectorType->id,
          // 'start_time' => $startTime,
          // 'end_time'   => $endTime,
          // 'min_kwt'    => $minKWT,
          // 'max_kwt'    => $maxKWT,
          // 'price'      => $price,
        ]
      )
      ->assertSessionHasErrors(
        [
          'start_time', 
          'end_time', 
          'min_kwt',
          'max_kwt',
          'price',
        ]
      );
  }

  /** @test */
  public function destroy_lvl_2_charging_price_is_ok(): void
  {
    $fastChargingPrice = factory(ChargingPrice::class)->create(
      [
        'charger_connector_type_id' => $this->chargerConnectorType->id,
      ],
    );

    $this
      ->actingAs($this->user)
      ->delete($this->destroyLVL2ChargingPrice($fastChargingPrice->id))
      ->assertSessionHasNoErrors();
    
    $this->chargerConnectorType->refresh();

    $lvl2ChargingPrices = $this->chargerConnectorType->charging_prices;

    $this->assertEmpty($lvl2ChargingPrices);
  }

  /** @test */
  public function destroy_fast_charging_price_is_forbidden(): void
  {
    $lvl2ChargingPrice = factory(ChargingPrice::class)->create(
      [
        'charger_connector_type_id' => factory(ChargerConnectorType::class) -> create([
          'charger_id' => factory(Charger::class)->create(),
        ]),
      ]
    );

    $this
      ->actingAs($this->user)
      ->delete($this->destroyLVL2ChargingPrice($lvl2ChargingPrice->id))
      ->assertForbidden();
  }
}