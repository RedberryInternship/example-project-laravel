<?php

namespace App\Http\Resources;

use App\Library\Entities\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionsHistory extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                => $this -> id,
            'charger_name'      => $this -> charger_name ,
            'start_date'        => $this -> start_date,
            'charge_price'      => $this -> charge_price,  
            'duration'          => Helper::convertMinutesToHHMM( $this -> duration ),
            'penalty_fee'       => $this -> penalty_fee,
            'penalty_duration'  => Helper::convertMinutesToHHMM( $this -> penalty_duration),
            'charge_power'      => null, // $this -> charge_power,
            'address'           => $this -> address,
            'user_card_pan'     => $this -> user_card -> masked_pan
        ];
    }
}
