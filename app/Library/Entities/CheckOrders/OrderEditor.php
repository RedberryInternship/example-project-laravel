<?php

namespace App\Library\Entities\CheckOrders;

use App\Library\DataStructures\RealChargerAttributes;
use App\Enums\OrderStatus as OrderStatusEnum;
use Illuminate\Support\Facades\Log;
use App\Facades\Charger;
use App\Order;

class OrderEditor
{
  /**
   * Build instance.
   * 
   * @return self
   */
  public static function instance(): self
  {
    return new self;
  }

  /**
   * @var int $chargerTransactionId
   */
  private $chargerTransactionId;

  /**
   * @var RealChargerAttributes $chargerAttributes
   */
  private $chargerAttributes;

  /**
   * @var Order $order
   */
  private $order;

  /**
   * Set real charger transaction id.
   * 
   * @param   int $chargerTransactionId
   * @return  self
   */
  public function setChargerTransactionId( $chargerTransactionId ): self
  {
    $this -> chargerTransactionId = $chargerTransactionId;
    return $this;
  }

  /**
   * Set real charger attributes.
   * 
   * @param  RealChargerAttributes $chargerAttributes
   * @return self
   */
  public function setChargerAttributes( RealChargerAttributes $chargerAttributes ): self
  {
    $this -> chargerAttributes = $chargerAttributes;
    return $this;
  }

  /**
   * Set order.
   * 
   * @param Order $order
   * @return self
   */
  public function setOrder( $order ): self
  {
    $this -> order = $order;
    return $this;
  }

  /**
   * Update order if exists.
   * 
   * @return void
   */
  public function digest(): void
  {
    if( $this -> shouldStop() )
    {
      $this -> stop();
    }
    else if( $this -> shouldContinueCharging() )
    {
      $this -> updateOrder();
    }
  }

  /**
   * find out if this is the order
   * that should be stopped.
   * 
   * @param  Order $order
   * @return bool
   */
  private function shouldStop(): bool
  {
    return ! $this -> order || in_array( $this -> order -> charging_status, $this -> ordersToStop());
  }

  /**
   * if status is on hold or not confirmed,
   * then charging should be continued...
   * 
   * @return bool
   */
  public function shouldContinueCharging(): bool
  {
    if( ! $this -> order )
    {
      return false;
    }

    $continuableStatuses = [ OrderStatusEnum :: ON_HOLD, OrderStatusEnum :: NOT_CONFIRMED ];

    return in_array( $this -> order -> charging_status, $continuableStatuses );
  }

  /**
   * Stop charging.
   * 
   * @return void
   */
  private function stop()
  {
    Log :: channel( 'orders-check' ) -> info( 'Stopped Transaction - '. $this -> chargerTransactionId );  
    
    $this -> order && $this -> order -> update([ 'checked' => true ]);

    Charger :: stop(
      $this -> chargerAttributes -> getChargerId(),
      $this -> chargerTransactionId,
    );
  }

  /**
   * Kind of orders to stop charging.
   * 
   * @return array
   */
  private function ordersToStop()
  {
    return [
      OrderStatusEnum :: CANCELED,
    ];
  }

  /**
   * Update order status which was not confirmed.
   * 
   * @return void
   */
  public function updateOrder()
  {
    $this -> order -> updateChargingStatus( OrderStatusEnum :: CHARGING );
  }
}