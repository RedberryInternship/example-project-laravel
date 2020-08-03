<?php

namespace App\Library\Entities\ChargingStart;

use App\Enums\OrderStatus as OrderStatusEnum;
use App\Enums\PaymentType as PaymentTypeEnum;

use App\Config;
use App\Order;

class FastChargerPayer
{
  /**
   * When starting charging process pay if it
   * successfully started charging and the charger type 
   * is FAST.
   * 
   * @param   Order $order
   * @param   bool  $isByAmount
   * @return  void
   */
  public static function pay( Order $order, bool $isByAmount, bool $isChargerFast ): void
  {
    if( ! $isChargerFast )
    {
      return;
    }

    if( ! $order -> charging_status == OrderStatusEnum :: CHARGING )
    {
        return;
    }

    if( $isByAmount )
    {
        $targetPrice = $order -> target_price;

        $order -> pay( PaymentTypeEnum :: CUT, $targetPrice );
    }
    else
    {
        $moneyToCut = Config :: initialChargePrice();
        $order -> pay( PaymentTypeEnum :: CUT, $moneyToCut );
    }
  }
}