<?php

namespace App\Http\Controllers\Api\app\V1\User;

use App\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrdersCollection;

class OrderController extends Controller
{
    public function __invoke(Order $order)
    {
        $user = auth('api') -> user();

        return new OrdersCollection(
            $order
                -> where('user_id', $user -> id)
                -> with(['charger_connector_type.charger' => function($query) {
                    return $query -> withAllAttributes();
                }])
                -> confirmedPaymentsWithUserCards()
                -> get()
        );
    }
}
