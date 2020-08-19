<?php

namespace App\Nova\Filters;

use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\BooleanFilter;

class OrderType extends BooleanFilter
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
        $shouldBeNull = false;

        foreach ($value as $status => $bool)
        {
            if( $status == 'NULL' )
            {
                $shouldBeNull = true;
                continue;
            }

            if ($bool)
            {
                $statuses[] = $status;
            }
        }

        $shouldBeNull && $query -> whereNull( 'charging_status' );

        return empty($statuses) ? $query : $query -> whereIn('charging_status', $statuses);
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
            OrderStatus::INITIATED      => OrderStatus::INITIATED,
            OrderStatus::CHARGING       => OrderStatus::CHARGING,
            OrderStatus::CHARGED        => OrderStatus::CHARGED,
            OrderStatus::USED_UP        => OrderStatus::USED_UP,
            OrderStatus::ON_HOLD        => OrderStatus::ON_HOLD,
            OrderStatus::ON_FINE        => OrderStatus::ON_FINE,
            OrderStatus::FINISHED       => OrderStatus::FINISHED,
            OrderStatus::CANCELED       => OrderStatus::CANCELED,
            OrderStatus::BANKRUPT       => OrderStatus::BANKRUPT,
            OrderStatus::UNPLUGGED      => OrderStatus::UNPLUGGED,
            OrderStatus::NOT_CONFIRMED  => OrderStatus::NOT_CONFIRMED,
            OrderStatus::PAYMENT_FAILED => OrderStatus::PAYMENT_FAILED,
            'NULL'                      => null,
        ];
    }
}
