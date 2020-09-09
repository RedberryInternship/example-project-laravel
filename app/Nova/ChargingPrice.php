<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\Select;

class ChargingPrice extends Resource
{
    public static $displayInNavigation = false;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\ChargingPrice';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $timeOptions = $this -> selectData();
        return [
            ID::make()->sortable(),
            HasOne::make('Charger Connector Type'),
            Text::make('Min Kwt'),
            Text::make('Max Kwt'),
            Select::make('Start Time') -> options( $timeOptions ),
            Select::make('End Time') -> options( $timeOptions ),
            Text::make('Price'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    /**
     * Prepare select timing data.
     * 
     * @return array
     */
    private function selectData(): array
    {
        $data = [];
        for($hour=0; $hour<=24; $hour++)
        {
            $hr          = $this -> digitFul($hour);
            $time        = $hr . ':' . '00';
            $data[$time] = $time;
        }
        return $data;
    }

    /**
     * Make number presented with 2 digits.
     * 
     * @return string
     */
    private function digitFul( $num ): string
    {
        if( $num < 10 )
        {
            return '0' . strval($num);
        }
        
        return strval($num);
    }
}
