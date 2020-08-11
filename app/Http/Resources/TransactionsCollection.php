<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TransactionsCollection extends ResourceCollection
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
            'charger_name'  => $request -> charger_name ,
            'start_date'    => $request -> start_date,
            'charge_price'  => $request -> charge_price,  
            'penalty_fee'   => $request -> penalty_fee,
            'duration'      => $request -> duration,
            'charge_power'  => $request -> charge_power,
            'address'       => $request -> address,
        ];
    }
}
