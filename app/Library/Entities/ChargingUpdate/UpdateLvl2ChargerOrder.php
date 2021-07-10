<?php

namespace App\Library\Entities\ChargingUpdate;

use App\Enums\PaymentType as PaymentTypeEnum;
use App\Enums\OrderStatus as OrderStatusEnum;
use App\Facades\Charger as RealCharger;
use App\Library\Entities\Helper;
use App\Order;

class UpdateLvl2ChargerOrder
{
  /**
   * Update lvl 2 charger order and make 
   * transactions if necessary.
   * 
   * @param Order $order
   * @return void
   */
  public static function execute( Order &$order ): void
  {
    if( $order -> isInitiated() && $order -> chargingHasStarted() )
    {
      $order -> updateChargingStatus( OrderStatusEnum :: CHARGING );   
      self :: makeFirstPayment( $order );
      $order -> updateChargingPowerRecords();
    }
    else if( $order -> isCharging() || $order->isOnHold() )
    {
      $order -> updateChargingPowerRecords();
      $charger = $order -> getCharger();
      
      if( ! $charger -> isPaid() || $order -> isChargingFree() )
      {
        return;
      }

      self :: handleByAmountChargingProcess( $order );
      self :: handleFullChargeChargingProcess( $order );

      if( $order -> carHasAlreadyCharged() ) 
      {
        self :: stopChargingWhenCharged( $order );
      }
    }
  }

  /**
   * Make first transaction payment.
   * 
   * @param Order $order
   * @return void
   */
  private static function makeFirstPayment( Order &$order ): void
  {
    $charger = $order -> getCharger();

    if( ! $charger -> isPaid() || $order -> isChargingFree() )
    {
      return;
    }

    if( $order -> isByAmount() )
    {
        $order -> pay( PaymentTypeEnum :: CUT, $order -> target_price );
    }
    else
    {
        $moneyToCut = Helper :: getInitialChargingPrice();
        $order -> pay( PaymentTypeEnum :: CUT, $moneyToCut );
    }
  }

  /**
   * Handle By amount type order 
   * update when charging.
   * 
   * @param Order $order
   * @return void
   */
  private static function handleByAmountChargingProcess( Order &$order ): void
  {
    if( $order -> isByAmount() && $order -> shouldPay() )
    {
      self :: stopChargingWhenUsedUp( $order );
    }
  }

  /**
   * Stop charging process when used up.
   * 
   * @param Order $order
   * @return void
   */
  private static function stopChargingWhenUsedUp( Order &$order ): void
  {
    $charger = $order -> getCharger();

    RealCharger :: stop( 
      $charger -> charger_id, 
      $order   -> charger_transaction_id 
    );

    $order -> updateChargingStatus( OrderStatusEnum :: USED_UP );
  }

  /**
   * Handle full charge type order
   * when charging.
   * 
   * @param Order $order
   * @return void
   */
  private static function handleFullChargeChargingProcess( Order &$order ): void
  {
    if( ! $order -> isByAmount() )
    {
      if( $order -> shouldPay() && (! $order -> isChargingFree()) )
      {
        self :: cutNextChargingPrice( $order );
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
   * Stop charging process when charged.
   * 
   * @param Order $order
   * @return void
   */
  private static function stopChargingWhenCharged( Order &$order ): void
  {
    $charger = $order -> getCharger();
    
    if(! RealCharger :: isCharging($charger -> charger_id))
    {
      return;
    }

    RealCharger :: stop( 
      $charger -> charger_id, 
      $order   -> charger_transaction_id 
    );

    $order -> updateChargingStatus( OrderStatusEnum :: CHARGED );
  }
}