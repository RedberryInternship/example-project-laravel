<?php

namespace Test\Unit;

use App\Whitelist as AppWhitelist;
use Tests\TestCase;
use App\Charger;
use Closure;

class Whitelist extends TestCase
{

  /**
   * Sample charger.
   * 
   * @var Charger $charger
   */
  private $charger;

  /**
   * API routes.
   * 
   * @var string $toggleChargerVisibilityURL
   * @var Closure $getChargerWhitelistURL
   * @var Closure $addToWhitelistURL
   * @var string $removeFromWhitelistURL
   */
  private $toggleChargerVisibilityURL = 'business/chargers/toggle-visibility';
  private function getChargerWhitelistURL($id) 
  { 
    return 'business/chargers/'. $id .'/whitelist'; 
  }
  private $addToWhitelistURL = 'business/chargers/add/whitelist';
  private $removeFromWhitelistURL = 'business/chargers/remove-from/whitelist';

  /**
   * Create sample charger.
   */
  protected function setUp(): void
  {
    parent::setUp();
    $this -> charger = factory(Charger::class)->create();

    $this 
      -> charger 
      -> whitelist() 
      -> createMany(
      [
        ['phone' => '+995591935080'],
        ['phone' => '+995591935081'],
        ['phone' => '+995591935082'],
      ]
    );
  }

  /** @test */
  public function toggle_charger_visibility_ok(): void
  {
    $this -> charger -> update(['hidden' => false]);

    $this
      -> post(
        $this -> toggleChargerVisibilityURL,
        [
          'charger_id' => $this -> charger -> id,
          'hidden' => true,
        ]
      )
      -> assertSessionHasNoErrors();
  }

  /** @test */
  public function toggle_charger_visibility_has_validation_errors(): void
  {
    $this -> charger -> update(['hidden' => false]);

    $this
      -> post(
        $this -> toggleChargerVisibilityURL,
        [
          // 'charger_id' => $this -> charger -> id,
          // 'hidden' => true,
        ]
      )
      -> assertSessionHasErrors(['charger_id', 'hidden']);
  }

  /** @test */
  public function get_charger_whitelist_ok(): void
  {
    $getChargerWhitelistURL = $this -> getChargerWhitelistURL( $this -> charger -> id );

    $this 
      -> get( $getChargerWhitelistURL )
      -> assertJsonCount(3);
  }

  /** @test */
  public function add_to_whitelist_ok(): void
  {
    $phone = '+995591000000';

    $this
      -> post(
        $this -> addToWhitelistURL,
        [
          'charger_id' => $this -> charger -> id,
          'phone' => $phone,
        ],
      )
      ->assertOk();
  }

  /** @test */
  public function add_to_whitelist_has_validation_errors(): void
  {
    $phone = '+995591000000';

    $this
      -> post(
        $this -> addToWhitelistURL,
        [
          // 'charger_id' => $this -> charger -> id,
          'phone' => $phone,
        ],
      )
      ->assertSessionHasErrors(['charger_id']);
  }

  /** @test */
  public function remove_from_whitelist_ok(): void
  {
    $whitelistedId = $this -> charger -> whitelist -> first() -> id;

    $this
      -> post(
        $this -> removeFromWhitelistURL,
        [
          'whitelist_id' => $whitelistedId,
        ] 
      )
      -> assertOk();

    $this -> charger -> refresh();

    $chargerWhitelist = $this -> charger -> whitelist;

    $this -> assertCount(2, $chargerWhitelist);
  }

  /** @test */
  public function remove_from_whitelist_has_validation_errors(): void
  {
    $whitelistedId = $this -> charger -> whitelist -> first() -> id;

    $this
      -> post(
        $this -> removeFromWhitelistURL,
        [
          // 'whitelist_id' => $whitelistedId,
        ] 
      )
      -> assertSessionHasErrors(['whitelist_id']);
  }
}