<?php

namespace Test\Unit;

use App\Enums\OrderStatus as OrderStatusEnum;
use Illuminate\Support\Collection;
use App\Enums\Role as RoleEnum;
use App\ChargerConnectorType;
use Tests\TestCase;
use App\Charger;
use App\Company;
use App\Order;
use App\Role;
use App\User;

class BusinessOrders extends TestCase
{
  /**
   * Business user.
   * 
   * @var User
   */
  private $user;

  /**
   * Company.
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
   * Business charger connector type.
   * 
   * @var ChargerConnectorType
   */
  private $chargerConnectorType;

  /**
   * Business orders.
   * 
   * @var Collection
   */
  private $orders;

  /**
   * Routes.
   */
  private $allOrdersURL = 'business/orders';
  private function showOrderURL($id) {
    return "business/orders/$id";
  }
  private $exportOrdersURL = 'business/order-exports';

  /**
   * Set up...
   */
  protected function setUp(): void
  {
    parent::setUp();

    $this -> company = factory(Company::class)->create();

    $this -> charger = factory(Charger::class)->create(
      [
        'company_id' => $this -> company -> id,
      ]
    );

    $this -> chargerConnectorType = factory(ChargerConnectorType::class)->create(
      [
        'charger_id' => $this -> charger -> id,
      ]
    );
    
    $businessRole = factory(Role::class) -> create(
      [ 
        'name' => RoleEnum::BUSINESS,
      ]
    );

    $this -> user = $this->createUser(
      [
        'company_id' => $this->company -> id,
        'role_id' => $businessRole -> id,
      ]
    );

    $this -> orders = factory(Order::class, 10)->create(
      [
        'charger_connector_type_id' => $this -> chargerConnectorType -> id,
        'user_id' => $this -> user -> id,
        'charging_status' => OrderStatusEnum::FINISHED,
      ]
    );
  }

  /** @test */
  public function all_orders_is_ok(): void
  {
    $this
      ->actingAs($this->user)
      ->get($this->allOrdersURL)
      ->assertOk();
  }

  /** @test */
  public function show_order_ok(): void
  {
    $orderId = $this->orders->first()->id;

    $this
      -> actingAs($this->user)
      -> get($this->showOrderURL($orderId))
      -> assertOk();
  }

  /** @test */
  public function show_order_returns_not_found_error(): void
  {
    $orderId = 10001;

    $this
      -> actingAs($this->user)
      -> get($this->showOrderURL($orderId))
      -> assertNotFound();
  }

  /** @test */
  public function download_orders_excel_is_ok(): void
  {
    $this
      -> actingAs($this->user)
      -> get($this->exportOrdersURL)
      -> assertOk();
  }
}