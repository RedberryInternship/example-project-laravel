<?php

namespace App\Nova\Filters\Order;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use App\Enums\ChargingType as ChargingTypeEnum;

class ChargingType extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

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
        if( $value == ChargingTypeEnum :: BY_AMOUNT )
        {
            $query -> where( 'charging_type', ChargingTypeEnum :: BY_AMOUNT );
        }
        else if( $value == ChargingTypeEnum :: FULL_CHARGE )
        {
            $query -> where( 'charging_type', ChargingTypeEnum :: FULL_CHARGE );
        }

        return $query;
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
            ChargingTypeEnum :: BY_AMOUNT   => ChargingTypeEnum :: BY_AMOUNT,
            ChargingTypeEnum :: FULL_CHARGE => ChargingTypeEnum :: FULL_CHARGE,
        ];
    }
}
