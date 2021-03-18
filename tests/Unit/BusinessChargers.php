<?php

namespace Test\Unit;

use App\User;
use Illuminate\Support\Collection;
use Tests\TestCase;

class BusinessChargers extends TestCase
{
  /**
   * Routes.
   */
  private $allChargersURL = 'business/chargers';
  private $showChargerURL = 'business/chargers';
  private $editChargerURL = 'business/chargers';
  private $updateChargerURL = 'business/chargers';
  private $filteredChargerURL = 'business/chargers';

  /**
   * Business chargers.
   * 
   * @var Collection
   */
  private $chargers;

  /**
   * Business user.
   * 
   * @var User
   */
  private $user;

  /**
   * Set up...
   */
  protected function setUp(): void
  {
    parent::setUp();
  }

  /** @test */
  public function get_all_chargers_is_ok(): void
  {
    //
  }
}