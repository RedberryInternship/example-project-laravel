<?php


namespace Test\Unit;

use App\Order;
use App\User;
use Carbon\Carbon;
use Tests\TestCase;

class ChargingPowerAndKilowatts extends TestCase
{ 
  /**
   * Order model.
   * 
   * @var Order
   */
  private $order;

  /**
   * User model.
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

    $this->user = $this->createUser();
    $this->order = factory(Order::class)->create(
      [
        'user_id' => $this->user->id,
      ]
    );
  }

  /** @test */
  public function first_kilowatts_value_creates_new_kilowatt_record(): void
  {
    $this->order->updateKilowattRecordAndChargingPower(0);
    $this->order->refresh();
    $orderKilowatt = $this->order->kilowatt;

    $this->assertNotNull($orderKilowatt);
  }

  /** @test */
  public function updated_at_values_are_correct(): void
  {
    $now = now();
    Carbon::setTestNow($now);
    $this->order->updateKilowattRecordAndChargingPower(0);

    $now->addMinutes(60);
    Carbon::setTestNow($now);
    $this->order->updateKilowattRecordAndChargingPower(6000);
    $this->order->refresh();
    
    $this -> assertEquals(6, $this->order->kilowatt->charging_power);
    

    $now->addMinutes(90);
    Carbon::setTestNow($now);
    $this->order->updateKilowattRecordAndChargingPower(6000 + 3000);
    $this->order->refresh();

    $this -> assertEquals(2, $this->order->kilowatt->charging_power);
    
    $now->addMinutes(30);
    Carbon::setTestNow($now);
    $this->order->updateKilowattRecordAndChargingPower(9000 + 1000);
    $this->order->refresh();

    $this -> assertEquals(2, $this->order->kilowatt->charging_power);
  }
  
  /** @test */
  public function updated_at_values_are_correct_by_seconds(): void
  {
    $now = now();
    Carbon::setTestNow($now);
    $this->order->updateKilowattRecordAndChargingPower(0);

    $now->addSeconds(27);
    /**
     * 27 seconds = 0.45 minutes = 0.0075hrs
     */
    Carbon::setTestNow($now);
    $this->order->updateKilowattRecordAndChargingPower(975);
    $this->order->refresh();
    
    $this -> assertEquals(130, $this->order->kilowatt->charging_power);
  }
}