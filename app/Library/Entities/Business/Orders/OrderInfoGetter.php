<?php

namespace App\Library\Entities\Business\Orders;

use App\Order;
use App\Library\Entities\ChargingProcess\Timestamp;

class OrderInfoGetter 
{
  /**
   * Get order info.
   * 
   * @param int $id
   * @return array
   */
  public static function get( $id )
  {
    $order      = Order :: with(
      [
        'charger_connector_type.charger',
        'kilowatt',
      ]
    ) -> find($id);
    
    $timestamp  = Timestamp :: build($order);
    $charger    = $order -> charger_connector_type -> charger;

    $id                 = $order -> id;
    $chargerCode        = $charger -> code;
    $chargerDescription = $charger -> getTranslation('location','ka');
    $chargerType        = $order -> charger_connector_type -> determineChargerType();
    $consumedKilowatts  = $order -> kilowatt ? $order -> kilowatt -> consumed : '0';
    $duration           = $order -> duration;
    $startTime          = $timestamp -> getStartTimestamp();
    $endTime            = $timestamp -> getOriginalEndTime();
    $chargeTime         = $timestamp -> getStopChargingTimestamp() ?? $endTime;
    $chargePower        = $order -> charge_power ? $order -> charge_power : '0';
    $chargePrice        = $order -> charge_price;
    $penaltyFee         = $order -> penalty_fee ? $order -> penalty_fee : '0' ;

    return [ 
      'ID'                  => $id,
      'charger_code'        => $chargerCode,
      'charger_description' => $chargerDescription,
      'charger_type'        => $chargerType,
      'consumed_kilowatts'  => $consumedKilowatts,
      'charge_power'        => $chargePower,
      'charge_duration'     => $duration,
      'start_time'          => $startTime,
      'charge_time'         => $chargeTime,
      'end_time'            => $endTime,
      'charge_price'        => $chargePrice,
      'penalty_fee'         => $penaltyFee,
    ];
  }
}