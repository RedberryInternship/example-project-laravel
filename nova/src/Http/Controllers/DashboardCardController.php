<?php

namespace Laravel\Nova\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\DashboardCardRequest;

class DashboardCardController extends Controller
{
    /**
     * List the cards for the dashboard.
     *
     * @param  \Laravel\Nova\Http\Requests\DashboardCardRequest  $request
     * @param  string  $dashboard
     * @return \Illuminate\Http\Response
     */

    /**
     * Get the URI key for the resource.
     *
     * @return string
     */
    public static function uriKey()
    {
        return 'kard';
    }

    public function index(DashboardCardRequest $request, $dashboard = 'main')
    {
        return $request->availableCards($dashboard);
    }
}
