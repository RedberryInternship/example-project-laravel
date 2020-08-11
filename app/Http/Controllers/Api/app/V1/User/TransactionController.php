<?php

namespace App\Http\Controllers\Api\app\V1\User;

use App\Http\Resources\TransactionsCollection;
use App\Http\Controllers\Controller;
use App\User;

class TransactionController extends Controller
{
    public function __invoke()
    {
        $userId       = auth() -> user() -> id;
        $transactions = User :: with( 'orders_history' ) -> find( $userId ) -> orders_history;

        return new TransactionsCollection( $transactions );
    }
}
