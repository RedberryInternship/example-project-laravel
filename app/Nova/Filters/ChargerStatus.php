<?php

namespace App\Nova\Filters;

use App\Enums\ChargerStatus as ChargerStatusEnum;
use Laravel\Nova\Filters\BooleanFilter;
use Illuminate\Http\Request;


class ChargerStatus extends BooleanFilter
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
        $statuses = [];
        foreach( $value as $key => $val )
        {
            $val && $statuses []= $key;
        }

        return empty( $statuses ) ? $query : $query -> whereIn( 'status', $statuses );
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return [
            ChargerStatusEnum :: ACTIVE         => ChargerStatusEnum :: ACTIVE,
            ChargerStatusEnum :: INACTIVE       => ChargerStatusEnum :: INACTIVE,
            ChargerStatusEnum :: CHARGING       => ChargerStatusEnum :: CHARGING,
            ChargerStatusEnum :: NOT_PRESENT    => ChargerStatusEnum :: NOT_PRESENT,
        ];
    }
}
