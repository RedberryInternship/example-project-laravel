<?php

namespace App\Library\Entities\ChargingProcess;

use App\Library\Entities\ChargingProcess\Timestamp;
use App\Order;

class CacheOrderDetails
{
  /**
   * Cache order details to easily access later.
   * 
   * @param  Order $order
   * @return void
   */
  public static function execute( Order &$order ): void
  {
    $instance = new self;
    $instance -> calculate( $order );
    $instance -> update   ( $order );
  }
  
  /**
   * Order details that should be updated.
   * 
   * @var array $details
   */
  private $details;

  /**
   * calculate update values.
   * 
   * @param  Order $order
   * @return void
   */
  private function calculate( Order &$order ): void
  { 
    $timestamp    = Timestamp :: build( $order );
    $charger      = $order -> getCharger();

    $this -> details = [
      'charger_name'        => $charger       -> name,
      'start_date'          => $timestamp     -> getStartTimestamp(),
      'charge_price'        => $order         -> countConsumedMoney(),
      'penalty_fee'         => $order         -> countPenaltyFee(),
      'duration'            => $timestamp     -> getChargingDuration(),
      'charge_power'        => $order         -> kilowatt -> charging_power,
      'address'             => $charger       -> location,
      'company_id'          => $charger       -> company_id,
      'consumed_kilowatts'  => @round($order  -> kilowatt -> consumed, 2),
      'penalty_duration'    => $timestamp     -> penaltyTimeInMinutes(),
    ];
  }

  /**
   * Update order details.
   * 
   * @return void
   */
  public function update( Order &$order ): void
  {
    $order -> update( $this -> details );
  }
}
