<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\OrderStatus as OrderStatusEnum;

class Order extends JsonResource
{
    /**
     * Set without wrapping property.
     * 
     * @var string|null
     */
    public static $wrap = null;

    /** 
     * Additional data 
     * 
     * @var array $additionalData
     */
    private $additionalData = [];

    /**
     * Override parent constructor
     * in order to pass additional data.
     * 
     * @param \App\Order    $resource
     * @param array         $additionalData
     */
    public function __construct( $resource, array $additionalData = [] )
    {
        parent :: __construct( $resource );

        $this -> setAdditionalData( $additionalData );
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {   
        $startChargingTime = $this -> charging_status_change_dates [ OrderStatusEnum :: CHARGING ];

        $mainResourceData = [
            'already_paid'                  => $this -> countPaidMoney(),
            'consumed_money'                => $this -> countConsumedMoney(),
            'refund_money'                  => $this -> countMoneyToRefund(),
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
