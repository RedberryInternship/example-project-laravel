<?php

namespace App\Nova\Filters\Charger;

use App\Charger;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use App\Enums\ChargerType as ChargerTypeEnum;

class ChargerType extends Filter
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
        if( $value == ChargerTypeEnum :: FAST )
        {
            $query -> whereIn( 'id', Charger :: getFastIds() );
        }
        else if( $value == ChargerTypeEnum :: LVL2 )
        {
            $query -> whereIn( 'id', Charger :: getLvl2Ids() );
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
            ChargerTypeEnum :: FAST => ChargerTypeEnum :: FAST,
            ChargerTypeEnum :: LVL2 => ChargerTypeEnum :: LVL2,
        ];
    }
}
