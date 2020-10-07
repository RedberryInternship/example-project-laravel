<?php

namespace App\Http\Controllers\Business\Analytics;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TransactionsController extends Controller
{
    /**
     * TransactionsController Constructor.
     */
    public function __construct()
    {
        $this -> middleware('business.auth');
    }

    /**
     * Get Business Transactions.
     */
    public function __invoke()
    {
        $user  = Auth::user();

        return response() -> json([
            'energy'       => $user -> businessWastedEnergyCount(),
            'transactions' => $user -> businessTransactionsCount(),
        ]);
    }
}
