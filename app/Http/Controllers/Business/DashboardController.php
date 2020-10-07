<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * DashboardController Constructor.
     */
    public function __construct()
    {
        $this -> middleware('business.auth');
    }

    /**
     * Dashboard page.
     */
    public function __invoke()
    {
        $user = Auth::user();

        return view('business.dashboard.index') -> with([
            'user'           => $user,
            'tabTitle'       => 'მთავარი',
            'activeMenuItem' => 'dashboard'
        ]);
    }
}
