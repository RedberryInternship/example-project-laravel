<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Order extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'already_paid'                  => $this -> countPaidMoney(),
            'consumed_money'                => $this -> countConsumedMoney(),
            'refund_money'                  => $this -> countMoneyToRefund(),
            'charging_status'               => $this -> charging_status,
            'charger_connector_type_id'     => $this -> charger_connector_type -> id, 
            'charger_id'                    => $this -> charger_connector_type -> charger -> id,
            'charger_code'                  => $this -> charger_connector_type -> charger -> code,
            'connector_type_id'             => $this -> charger_connector_type -> connector_type -> id,
            'user_card_id'                  => $this -> user_card_id,
        ];
    }
}
