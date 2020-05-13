<?php

namespace Tests\Unit\ChargerConnectorTypes;

use Tests\Traits\Helper;
use Tests\TestCase;
use Carbon\Carbon;

use App\Enums\ConnectorType as ConnectorTypeEnum;
use App\Enums\ChargerType as ChargerTypeEnum;

use App\ChargerConnectorType;
use App\ConnectorType;
use App\ChargingPrice;

class Entity extends TestCase
{
  use Helper;

  private $chargerConnectorType;

  protected function setUp(): void
  {
    parent::setUp();
    $this -> artisan( 'migrate' );
    $this -> chargerConnectorType = factory( ChargerConnectorType :: class ) -> create();
  }

  /** @test */
  public function it_can_determine_charger_type()
  {
    $connectorType = ConnectorType :: whereName( ConnectorTypeEnum :: CHADEMO ) -> first();

    $chargerConnectorType = factory( ChargerConnectorType :: class ) -> create(
      [
        'connector_type_id' => $connectorType -> id,
      ]
    );

    $chargerType = $chargerConnectorType -> determineChargerType();

    $this -> assertEquals( ChargerTypeEnum :: FAST, $chargerType );
  }

  /** @test */
  public function it_can_get_specific_charging_price()
  {    
    $chargerConnectorType = $this -> chargerConnectorType;

    factory( ChargingPrice :: class ) -> create(
      [
        'min_kwt'                 => 0,
        'max_kwt'                 => 5,
        'start_time'              => '00:00',
        'end_time'                => '12:00',
        'charger_connector_type_id'  => $chargerConnectorType -> id,
        'price'                   => 5,
      ]
    );

    factory( ChargingPrice :: class ) -> create(
      [
        'min_kwt'                 => 6,
        'max_kwt'                 => 10000,
        'start_time'              => '00:00',
        'end_time'                => '12:00',
        'charger_connector_type_id'  => $chargerConnectorType -> id,
        'price'                   => 60
      ]
    );

    factory( ChargingPrice :: class ) -> create(
      [
        'min_kwt'                 => 0,
        'max_kwt'                 => 5,
        'start_time'              => '12:01',
        'end_time'                => '24:00',
        'charger_connector_type_id'  => $chargerConnectorType -> id,
        'price'                   => 10
      ]
    );

    factory( ChargingPrice :: class ) -> create(
      [
        'min_kwt'                 => 6,
        'max_kwt'                 => 10000,
        'start_time'              => '12:01',
        'end_time'                => '24:00',
        'charger_connector_type_id'  => $chargerConnectorType -> id,
        'price'                   => 557,
      ]
    );

    $chargingPrice1 = $chargerConnectorType -> getSpecificChargingPrice(  3,  '03:57:00' );
    $chargingPrice2 = $chargerConnectorType -> getSpecificChargingPrice( 10,  '08:32:00' );
    $chargingPrice3 = $chargerConnectorType -> getSpecificChargingPrice(  4,  '12:50:00' );
    $chargingPrice4 = $chargerConnectorType -> getSpecificChargingPrice( 99,  '15:56:00' );

    $this -> assertEquals( $chargingPrice1 -> price, 5 );
    $this -> assertEquals( $chargingPrice2 -> price, 60 );
    $this -> assertEquals( $chargingPrice3 -> price, 10 );
    $this -> assertEquals( $chargingPrice4 -> price, 557 );
  }

  /** @test */
  public function it_can_count_fast_charging_price_based_on_current_time()
  {
    $chargerConnectorType = $this -> chargerConnectorType;

    $this -> make_fast_charging_prices( $chargerConnectorType -> id );

    // 10 minutes passed since start charging
    $fastChargingPrices = $chargerConnectorType -> collectFastChargingPriceRanges( 10 );
    $this -> assertCount( 1, $fastChargingPrices );
    
    
    // 15 minutes passed since start charging
    $fastChargingPrices = $chargerConnectorType -> collectFastChargingPriceRanges( 15 );
    $this -> assertCount( 2, $fastChargingPrices );
    

    // 25 minutes passed since start charging
    $fastChargingPrices = $chargerConnectorType -> collectFastChargingPriceRanges( 25 );

    $this -> assertCount( 3, $fastChargingPrices );
  }
}