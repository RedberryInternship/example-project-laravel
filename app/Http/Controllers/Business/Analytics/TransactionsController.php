<?php

namespace App\Http\Controllers\Business\Analytics;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

        return response() -> json($user -> businessTransactionsCount());
    }
}
