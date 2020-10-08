<?php

namespace App\Http\Controllers\Business\Analytics;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ActiveChargersController extends Controller
{
    /**
     * ActiveChargersController Constructor.
     */
    public function __construct()
    {
        $this -> middleware('business.auth');
    }

    /**
     * Get Business Active Chargers.
     */
    public function __invoke()
    {
        $user  = Auth::user();

        return response() -> json($user -> businessActiveChargers());
    }
}
