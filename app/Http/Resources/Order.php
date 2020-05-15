<?php

namespace App\Http\Resources;

use App\Config;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\ChargingType as ChargingTypeEnum;
use App\Enums\OrderStatus as OrderStatusEnum;

class Order extends JsonResource
{
    /**
     * Set without wrapping property
     * onto resource.
     * However, this won't work on collections.
     */
    public function __construct( $resource )
    {
        parent :: __construct( $resource );
        static :: withoutWrapping();
    }

    /** 
     * Additional data 
     * 
     * @var array $additionalData
     */
    private $additionalData = [];

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {        
        $startChargingTime = $this -> getChargingStatusTimestampInMilliseconds( OrderStatusEnum :: CHARGING );
	

        /**
         * If car is already charged or money is used up 
         * check if it is also on fine.
         */
        if(     $this -> charging_status == OrderStatusEnum :: CHARGED 
            ||  $this -> charging_status == OrderStatusEnum :: USED_UP )
	        {
                if( $this -> isOnFine() ) 
                {
                    $this -> updateChargingStatus( OrderStatusEnum :: ON_FINE); 
                }
            }

        /**
         * Add target price if it charging type is BY_AMOUNT.
         */
        if( $this -> target_price )
        {
            $this -> setAdditionalData(
                [
                    'target_price' => $this -> target_price,
                ]
            );
        }

        if( $this -> enteredPenaltyReliefMode() )
        {
            $penaltyReliefModeStartTime = $this -> charging_type == ChargingTypeEnum :: BY_AMOUNT
                ? $this -> getChargingStatusTimestamp( OrderStatusEnum :: USED_UP )
                : $this -> getChargingStatusTimestamp( OrderStatusEnum :: CHARGED );

            $config               = Config :: first();
            $penaltyReliefMinutes = $config -> penalty_relief_minutes;
            $penaltyStartTime     = $penaltyReliefModeStartTime -> addMinutes( $penaltyReliefMinutes );
            $penaltyStartTime     = $penaltyStartTime -> timestamp * 1000;

            $this -> setAdditionalData(
                [
                    'penalty_start_time' => $penaltyStartTime,
                ]
            );
        }
        
        if( $this -> isOnPenalty() )
        {            
            $this -> setAdditionalData(
                [
                    'penalty_fee'        => $this -> countPenaltyFee(),
                ]
            ); 
        }

        $mainResourceData = [
            'order_id'                      => $this -> id,
            'already_paid'                  => $this -> countPaidMoney(),
            'consumed_money'                => $this -> countConsumedMoney(),
            'refund_money'                  => $this -> countMoneyToRefund(),
            'charger_type'                  => $this -> charger_connector_type -> determineChargerType(),
            'start_charging_time'           => $startChargingTime,
            'charging_type'                 => $this -> charging_type,
            'charging_status'               => $this -> charging_status,
            'charger_connector_type_id'     => $this -> charger_connector_type -> id, 
            'charger_id'                    => $this -> charger_connector_type -> charger -> id,
            'charger_code'                  => $this -> charger_connector_type -> charger -> code,
            'connector_type_id'             => $this -> charger_connector_type -> connector_type -> id,
            'user_card_id'                  => $this -> user_card_id,
        ];

        return array_merge( $mainResourceData,  $this -> additionalData );
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
}
