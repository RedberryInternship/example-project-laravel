<?php

namespace App\Http\Controllers\Business\Analytics;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChargerStatusesController extends Controller
{
    /**
     * ChargerStatusesController Constructor.
     */
    public function __construct()
    {
        $this -> middleware('business.auth');
    }

    /**
     * Get Business Charger Statuses.
     */
    public function __invoke()
    {
        $user  = Auth::user();

        return response() -> json([
            'lvl2' => $user -> businessChargerStatuses(['Type 2']),
            'fast' => $user -> businessChargerStatuses(['Combo 2', 'CHAdeMO']),
        ]);
    }
}
