<?php

namespace App\Library\Presenters;

use App\Library\Entities\ChargingProcess\Timestamp;
use App\Enums\OrderStatus as OrderStatusEnum;
use App\Traits\Message;
use App\Order;

class ChargingProcess
{
  use Message;
  /**
   * Built data.
   * 
   * @var $chargingProcessData
   */
  private $chargingProcessData;

  /** 
   * Additional data 
   * 
   * @var array $additionalData
   */
  private $additionalData = [];

  /**
   * Construct presenter object.
   * 
   * @param Order $order
   */
  function __construct( Order $order )
  {
    $startChargingTime = Timestamp :: build( $order ) -> getChargingStatusTimestampInMilliseconds( OrderStatusEnum :: CHARGING );

    $this -> triggerPenaltyChecker            ( $order );
    $this -> addTargetPriceWhenByAmount       ( $order );
    $this -> addTimestampIfEnteredPenaltyMode ( $order );
    $this -> addPenaltyFeeIfOnPenalty         ( $order );
    $this -> setFinishedMessageWhenFinished   ( $order );
    $this -> setIsChargingFreeAttribute       ( $order );
        
    $mainResourceData = [
      'order_id'                      => $order -> id,
      'already_paid'                  => $order -> countPaidMoney(),
      'consumed_money'                => $order -> countConsumedMoney(),
      'refund_money'                  => $order -> countMoneyToRefund(),
      'charger_type'                  => $order -> charger_connector_type -> determineChargerType(),
      'start_charging_time'           => $startChargingTime,
      'charging_type'                 => $order -> charging_type,
      'charging_status'               => $order -> charging_status,
      'charger_connector_type_id'     => $order -> charger_connector_type -> id, 
      'charger_id'                    => $order -> getCharger() -> id,
      'charger_code'                  => $order -> getCharger() -> code,
      'connector_type_id'             => $order -> charger_connector_type -> connector_type -> id,
      'user_card_id'                  => $order -> user_card_id,
    ];

    $this -> chargingProcessData = array_merge( $mainResourceData,  $this -> additionalData );
    
    return $this;
  }

  /**
   * build presenter data.
   * 
   * @param  Order $order
   * @return self
   */
  public static function build( Order $order )
  {
    return new self( $order );
  }

  /**
   * Return built data.
   * 
   * @return array
   */
  public function resolve()
  {
    return $this -> chargingProcessData;
  }

  /**
   * Set additional data for resource.
   * 
   * @param   array $additionalData
   * @return  array
   */
  public function setAdditionalData( array $additionalData ): void
  {
      $this -> additionalData = array_merge( $this -> additionalData, $additionalData );
  }

  /**
   * If car is already charged or money is used up 
   * check if it is also on fine and if so update charging status.
   *
   * @param  Order $order 
   * @return void
   */
  private function triggerPenaltyChecker( Order $order )
  {
    if( $order -> charger_connector_type -> isChargerFast() )
    {
      return;
    }

    if( $order -> shouldGoToPenalty() )
    {
        $order -> updateChargingStatus( OrderStatusEnum :: ON_FINE); 
    }
  }

  /**
   * Add target price if it charging type is BY_AMOUNT.
   * 
   * @param  Order $order
   * @return void
   */
  private function addTargetPriceWhenByAmount( Order $order )
  {
    if( $order -> target_price )
    {
        $this -> setAdditionalData(
            [
                'target_price' => $order -> target_price,
            ]
        );
    }
  }

  /**
   * Add penalty timestamp if entered that mode.
   * 
   * @param  Order $order
   * @return void
   */
  private function addTimestampIfEnteredPenaltyMode( Order $order )
  {
    if( $order -> enteredPenaltyReliefMode() )
    {
        $this -> setAdditionalData(
            [
                'penalty_start_time' => Timestamp :: build( $order ) -> calculatePenaltyStartTime(),
            ]
        );
    }
  }

  /**
   * Add penalty fee field if on penalty or finished charging.
   * 
   * @param  Order $order
   * @return void
   */
  private function addPenaltyFeeIfOnPenalty( Order $order )
  {
    if( $order -> isOnPenalty() )
    {            
        $this -> setAdditionalData(
            [
                'penalty_fee' => $order -> countPenaltyFee(),
            ]
        ); 
    }
  }

  /**
   * Set finished message when order has finished indicator.
   * 
   * @param Order $order
   * @return void
   */
  private function setFinishedMessageWhenFinished( Order $order )
  {
    if( $order -> finished )
    {
      $this -> setAdditionalData(
        [
          'message' => $this -> messages [ 'charging_successfully_finished' ],
        ]
      );
    }
  }

  /**
   * Set isChargingFree attribute.
   * 
   * @param  Order $order
   * @return void
   */
  private function setIsChargingFreeAttribute( Order $order )
  {
    $isChargingFree = $order -> isChargingFree();

    if( ! is_null( $isChargingFree ) )
    {
      $this -> setAdditionalData(
        [
          'is_charging_free' => $isChargingFree,
        ]
      );
    }
  }
}