<?php

namespace App\Nova\Filters;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\DateFilter;

class EndDateRange extends DateFilter
{
    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        $endDate = Carbon :: parse( $value );

        $value && $query->whereDate('created_at', '<=', $endDate );

        return $query;
    }
}