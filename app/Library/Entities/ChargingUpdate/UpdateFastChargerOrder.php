<?php

namespace App\Library\Entities\ChargingUpdate;

use App\Enums\OrderStatus as OrderStatusEnum;
use App\Enums\PaymentType as PaymentTypeEnum;
use App\Facades\Charger as RealCharger;
use App\Library\Entities\Helper;
use App\Facades\Simulator;
use App\Order;

class UpdateFastChargerOrder
{
  /**
   * Update fast charger order and if necessary 
   * make payment transactions.
   * 
   * @param Order $order
   * @return void
   */
  public static function execute( Order &$order )
  {
    $order -> updateChargingPowerRecords();
    $charger = $order -> getCharger();

    if( $order -> isCharging() && $charger -> isPaid() )
    {
        if( $order -> isByAmount() )
        {
          $order -> shouldPay() && self :: stopCharging( $order );
        }
        else
        {
          $order -> shouldPay() && self :: cutNextChargingPrice( $order );
        }
    }
  }

  /**
   * Cut next charging price.
   * 
   * @param Order $order
   * @return void
   */
  private static function cutNextChargingPrice( Order &$order ): void
  {
    $moneyToCut = Helper :: getNextChargingPrice();
    $order -> pay( PaymentTypeEnum :: CUT, $moneyToCut );
  }

  /**
   * Stop charging and update charging status.
   * 
   * @param Order $order
   * @return void
   */
  private static function stopCharging( Order &$order ): void
  {
    $charger = $order -> getCharger();

    RealCharger :: stop( 
        $charger -> charger_id, 
        $order -> charger_transaction_id,
    );

    $order -> updateChargingStatus( OrderStatusEnum :: USED_UP );
    
    # GLITCH
    if(Helper :: isDev())
    {
        Simulator :: plugOffCable( $charger -> charger_id );
    }
  }
}