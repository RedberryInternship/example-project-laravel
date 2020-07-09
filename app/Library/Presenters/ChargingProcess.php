<?php

namespace App\Library\Presenters;

use App\Library\Entities\ChargingProcess\Timestamp;
use App\Enums\OrderStatus as OrderStatusEnum;

use App\Order;

class ChargingProcess
{
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
  function __construct( $order )
  {
    $startChargingTime = Timestamp :: build( $order ) -> getChargingStatusTimestampInMilliseconds( OrderStatusEnum :: CHARGING );

    $this -> updateChargingStatus             ( $order );
    $this -> addTargetPriceWhenByAmount       ( $order );
    $this -> addTimestampIfEnteredPenaltyMode ( $order );
    $this -> addPenaltyFeeIfOnPenalty         ( $order );
        
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
      'charger_id'                    => $order -> charger_connector_type -> charger -> id,
      'charger_code'                  => $order -> charger_connector_type -> charger -> code,
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
  public static function build( $order )
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
    $this -> chargingProcessData;
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
  private function updateChargingStatus( $order )
  {
    if( $order -> carHasAlreadyStoppedCharging() && $order -> isOnFine() )
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
  private function addTargetPriceWhenByAmount( $order )
  {
    if( $order -> target_price )
    {
        $this -> setAdditionalData(
            [
                'target_price' => $this -> target_price,
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
  private function addTimestampIfEnteredPenaltyMode( $order )
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
  private function addPenaltyFeeIfOnPenalty( $order )
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
}