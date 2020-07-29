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
        $user             = auth('api') -> user();

        $favoriteChargers = $user -> favorites -> pluck('id') -> toArray();

        $orders = $order -> where('user_id', $user -> id)
                         -> with(['charger_connector_type.charger' => function($query) {
                             return $query -> withAllAttributes();
                         }])
                         -> orderBy('id', 'DESC')
                         -> take($quantity)
                         -> get();

        $chargers = [];
        foreach ($orders as $order)
        {
            $chargers[] = $order -> charger_connector_type -> charger;
        }

        $charger -> addFilterAttributeToChargers($chargers, $favoriteChargers);

        Charger::addIsFreeAttributeToChargers($chargers);

        return new ChargerCollection($chargers);
    }
}
