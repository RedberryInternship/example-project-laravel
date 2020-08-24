<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use App\Enums\ChargerType as ChargerTypeEnum;
use App\Charger;

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
            $query -> whereIn( 'id', $this -> getFastIds() );
        }
        else if( $value == ChargerTypeEnum :: LVL2 )
        {
            $query -> whereIn( 'id', $this -> getLvl2Ids() );
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

    /**
     * get lvl 2 charger ids.
     * 
     * @return array
     */
    private function getLvl2Ids(): array
    {
        $ids = [];

        foreach( Charger :: types() as $key => $val )
        {
            if( $val == ChargerTypeEnum :: LVL2 )
            {
                $ids []= $key;
            }
        }

        return $ids;
    }
    
    /**
     * get fast charger ids.
     * 
     * @return array
     */
    private function getFastIds(): array
    {
        $ids = [];

        foreach( Charger :: types() as $key => $val )
        {
            if( $val == ChargerTypeEnum :: FAST )
            {
                $ids []= $key;
            }
        }

        return $ids;
    }
}
