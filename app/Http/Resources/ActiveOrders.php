<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActiveOrders extends JsonResource
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
            'charger'                       => [
                'name'        => $this -> charger_connector_type -> charger -> name,
                'code'        => $this -> charger_connector_type -> charger -> code,
                'description' => $this -> charger_connector_type -> charger -> description,
                'location'    => $this -> charger_connector_type -> charger -> location,
                'lat'         => $this -> charger_connector_type -> charger -> lat,
                'lng'         => $this -> charger_connector_type -> charger -> lng,
            ],
            'connector_type'                => [
                'name'        => $this -> charger_connector_type -> connector_type -> name,
            ],
        ];
    }
}
