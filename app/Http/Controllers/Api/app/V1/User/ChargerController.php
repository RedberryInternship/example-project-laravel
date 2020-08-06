<?php

namespace App\Http\Controllers\Api\app\V1\User;

use App\Order;
use App\Charger;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChargerCollection;

class ChargerController extends Controller
{
    public function __invoke(Order $order, Charger $charger, $quantity = 3)
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
            if ( ! in_array($order -> charger_connector_type -> charger -> id, $chargerIDs))
            {
                $chargers[]   = $order -> charger_connector_type -> charger;

                $chargerIDs[] = $order -> charger_connector_type -> charger -> id;
            }
        }

        Charger::addFilterAttributeToChargers($chargers);

        Charger::addChargingPrices($chargers);

        Charger::addIsFreeAttributeToChargers($chargers);

        return new ChargerCollection($chargers);
    }
}
