<?php

namespace App\Http\Controllers\Api\app\V1\User;

use App\Http\Resources\TransactionsHistory;
use App\Http\Controllers\Controller;
use App\User;

class TransactionController extends Controller
{
    public function __invoke()
    {
        $user           = User :: find( auth() -> user() -> id );
        $transactions   = $user -> ordersHistory();
        
        return TransactionsHistory :: collection( $transactions );
    }
}
