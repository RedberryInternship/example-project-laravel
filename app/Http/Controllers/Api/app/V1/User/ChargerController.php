<?php

namespace App\Http\Controllers\Api\app\V1\User;

use App\Order;
use App\Charger;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChargerCollection;

class ChargerController extends Controller
{
    /**
     * Last used chargers.
     * 
     * @param Order $order
     * @param int   $quantity
     * @return JSON
     */
    public function __invoke(Order $order, $quantity = 3)
    {
        $user   = auth('api') -> user();

        $orders = $order -> where('user_id', $user -> id)
                         -> with(['charger_connector_type.charger' => function($query) {
                             return $query -> withAllAttributes();
                         }])
                         -> orderBy('id', 'DESC')
                         -> take($quantity)
                         -> get();

        $chargers   = [];
        $chargerIDs = [];
        foreach ($orders as $order)
        {
            if ( ! in_array($order -> getCharger() -> id, $chargerIDs))
            {
                $chargers[]   = $order -> getCharger();

                $chargerIDs[] = $order -> getCharger() -> id;
            }
        }

        Charger::addIsFavoriteAttributes($chargers);

        Charger::addChargingPrices($chargers);

        Charger::addIsFreeAttributes($chargers);

        return new ChargerCollection($chargers);
    }
}
