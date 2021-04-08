<?php

namespace Test\Unit;

use App\Enums\ConnectorType as EnumsConnectorType;
use App\ChargerConnectorType;
use App\FastChargingPrice;
use App\ConnectorType;
use Tests\TestCase;
use App\Charger;
use App\Company;
use App\User;

class BusinessFastChargingPrices extends TestCase
{
  /** 
   * Route URLs.
   */ 
  private $storeFastChargingPriceURL = 'business/fast-charging-prices';
  private function destroyFastChargingPrice($id) { return "business/fast-charging-prices/$id"; }

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
        'connector_type_id' => ConnectorType::whereName(EnumsConnectorType::COMBO_2)->first()->id,
      ]
    );
  }

  /** @test */
  public function store_fast_charging_price_is_ok(): void
  {
    $startMinutes = 0;
    $endMinutes = 10;
    $price = 1;

    $this
      ->actingAs($this->user)
      ->post(
        $this->storeFastChargingPriceURL,
        [
          'charger_connector_type_id' => $this->chargerConnectorType->id,
          'start_minutes'             => $startMinutes,
          'end_minutes'               => $endMinutes,
          'price'                     => $price,
        ]
      )
      ->assertStatus(201);

    $this->charger->refresh();

    $this->chargerConnectorType->refresh();
    $fastChargingPricesCount = $this->chargerConnectorType->fast_charging_prices->count();

    $this->assertTrue($fastChargingPricesCount === 1);
  }
  
  /** @test */
  public function store_fast_charging_price_is_not_found(): void
  {
    $startMinutes = 0;
    $endMinutes = 10;
    $price = 1;

    $this
      ->actingAs($this->user)
      ->post(
        $this->storeFastChargingPriceURL,
        [
          'charger_connector_type_id' => 10001,
          'start_minutes'             => $startMinutes,
          'end_minutes'               => $endMinutes,
          'price'                     => $price,
        ]
      )
      ->assertNotFound();
  }
  
  /** @test */
  public function store_fast_charging_price_is_forbidden(): void
  {
    $startMinutes = 0;
    $endMinutes = 10;
    $price = 1;

    $intruderChargerConnectorType = factory(ChargerConnectorType::class)->create();

    $this
      ->actingAs($this->user)
      ->post(
        $this->storeFastChargingPriceURL,
        [
          'charger_connector_type_id' => $intruderChargerConnectorType->id,
          'start_minutes'             => $startMinutes,
          'end_minutes'               => $endMinutes,
          'price'                     => $price,
        ]
      )
      ->assertForbidden();
  }
 
  /** @test */
  public function store_fast_charging_price_has_validation_errors(): void
  {
    $this
      ->actingAs($this->user)
      ->post(
        $this->storeFastChargingPriceURL,
        [
          // 'charger_connector_type_id' => $this->chargerConnectorType->id,
          // 'start_minutes'             => $startMinutes,
          // 'end_minutes'               => $endMinutes,
          // 'price'                     => $price,
        ]
      )->assertSessionHasErrors(
        [
          'start_minutes', 
          'end_minutes', 
          'price',
        ]
      );
  }

  /** @test */
  public function destroy_fast_charging_price_is_ok(): void
  {
    $fastChargingPrice = factory(FastChargingPrice::class)->create(
      [
        'charger_connector_type_id' => $this->chargerConnectorType->id,
      ],
    );

    $this
      ->actingAs($this->user)
      ->delete($this->destroyFastChargingPrice($fastChargingPrice->id))
      ->assertSessionHasNoErrors();
    
    $this->chargerConnectorType->refresh();

    $fastChargingPrices = $this->chargerConnectorType->fast_charging_prices;

    $this->assertEmpty($fastChargingPrices);
  }

  /** @test */
  public function destroy_fast_charging_price_is_forbidden(): void
  {
    $fastChargingPrice = factory(FastChargingPrice::class)->create(
      [
        'charger_connector_type_id' => factory(ChargerConnectorType::class) -> create([
          'charger_id' => factory(Charger::class)->create(),
        ]),
      ]
    );

    $this
      ->actingAs($this->user)
      ->delete($this->destroyFastChargingPrice($fastChargingPrice->id))
      ->assertForbidden();
  }
}