<?php

namespace App\Library\Entities\ChargingStart;

use App\Enums\PaymentType as PaymentTypeEnum;
use App\Config;
use App\Order;

class FastChargerPayer
{
  /**
   * Order.
   * 
   * @var Order $order
   */
  private $order;

  /**
   * Is charging by amount.
   * 
   * @var bool $isByAmount
   */
  private $isByAmount;

  /**
   * Is charger fast.
   * 
   * @var bool $isChargerFast
   */
  private $isChargerFast;

  /**
   * Return new instance.
   * 
   * @return self
   */
  public static function instance(): self
  {
    return new self;
  }

  /**
   * Set order.
   * 
   * @param  Order $order
   * @return self
   */
  public function setOrder( Order $order ): self
  {
    $this -> order = $order;
    return $this;
  }

  /**
   * Set is by amount.
   * 
   * @param  bool $isByAmount
   * @return self
   */
  public function setIsByAmount( bool $isByAmount ): self
  {
    $this -> isByAmount = $isByAmount;
    return $this;
  }

  /**
   * Set is charger fast.
   * 
   * @param  bool $isChargerFast
   * @return self
   */
  public function setIsChargerFast( bool $isChargerFast ): self
  {
    $this -> isChargerFast = $isChargerFast;
    return $this;
  }

  /**
   * When starting charging process pay if it
   * successfully started charging and the charger type 
   * is FAST.
   * 
   * @return  void
   */
  public function pay(): void
  {
    $charger = $this -> order -> charger_connector_type -> charger;

    if( ! $charger -> isPaid() || ! $this -> isChargerFast || ! $this -> order -> isCharging() )
    {
      return;
    }

    if( $this -> isByAmount )
    {
        $targetPrice = $this -> order -> target_price;

        $this -> order -> pay( PaymentTypeEnum :: CUT, $targetPrice );
    }
    else
    {
        $moneyToCut = Config :: initialChargePrice();
        $this -> order -> pay( PaymentTypeEnum :: CUT, $moneyToCut );
    }
  }
}