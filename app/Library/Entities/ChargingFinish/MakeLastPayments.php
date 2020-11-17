<?php

namespace App\Library\Entities\ChargingFinish;

use App\Enums\PaymentType as PaymentTypeEnum;
use App\Order;

class MakeLastPayments
{
  /**
   * Make last payments for the finishing order.
   * 
   * @param Order $order
   * @return void
   */
  public static function execute( Order &$order )
  {
    if( $order -> charger_connector_type -> isChargerFast() )
      {
        self :: makeLastPaymentsForFastCharging( $order );
      }
      else 
      {
        self :: makeLastPaymentsForLvl2Charging( $order );
      }
  }

  /**
   * Charge the user or refund
   * accordingly, when fast charging.
   * 
   * @return void
   */
  private static function makeLastPaymentsForFastCharging( Order &$order )
  {
      self :: cutOrRefund( $order );
  }

  /**
   * Charge the user or refund
   * accordingly, when lvl 2 charging.
   * 
   * @return void
   */
  private static function makeLastPaymentsForLvl2Charging( Order &$order )
  {
    $charger = $order -> charger_connector_type -> charger;

    if( $charger->isPaid() && ! $order -> isChargingFree() )
    {
      self :: cutOrRefund( $order );
    }

    if( $charger -> isPenaltyEnabled() && $order -> isOnPenalty() )
    {
      $penaltyFee = $order -> countPenaltyFee();   
      $order -> pay( PaymentTypeEnum :: FINE, $penaltyFee );
    }
  }

  /**
   * Cut/refund or do 
   * nothing according data.
   * 
   * @return void
   */
  private static function cutOrRefund( Order &$order )
  {
      if( $order -> shouldPay() )
      {
          $shouldCutMoney = $order -> countMoneyToCut();
          $order -> pay( PaymentTypeEnum :: CUT, $shouldCutMoney );
      }
      else if( $order -> shouldRefund() )
      {
          $moneyToRefund  = $order -> countMoneyToRefund();
          $order -> pay( PaymentTypeEnum :: REFUND, $moneyToRefund );
      }
  }
}