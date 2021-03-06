<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Order;

class DashboardController extends Controller
{
    /**
     * Dashboard page.
     */
    public function __invoke()
    {
        return view('business.dashboard.index') -> with([
            'user'           => auth() -> user(),
            'tabTitle'       => __('business.sidebar.main'),
            'activeMenuItem' => 'dashboard',
            'firstYear'      => $this -> firstOrderYear(),
            'companyName'    => auth() -> user() -> company -> name,
        ]);
    }

    /**
     * First order year.
     * 
     * @return int $year
     */
    private function firstOrderYear(): ?int
    {
        $companyId = auth() -> user() -> company_id;
        $order = Order :: whereCompanyId($companyId) -> first();
        return $order ? $order -> created_at -> year : now() -> year; 
    }
}
