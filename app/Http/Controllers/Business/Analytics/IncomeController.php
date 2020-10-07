<?php

namespace App\Http\Controllers\Business\Analytics;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class IncomeController extends Controller
{
    /**
     * IncomeController Constructor.
     */
    public function __construct()
    {
        $this -> middleware('business.auth');
    }

    /**
     * Get Business Income.
     */
    public function __invoke()
    {
        $user  = Auth::user();

        return response() -> json([
            'income'  => $user -> businessIncomeCount(),
            'expense' => $user -> businessExpenseCount(),
        ]);
    }
}
