<?php

namespace App\Http\Controllers\Business;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

        return view('business.dashboard') -> with([
            'user'           => $user,
            'tabTitle'       => 'მთავარი',
            'activeMenuItem' => 'dashboard'
        ]);
    }
}
