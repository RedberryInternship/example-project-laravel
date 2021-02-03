<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class ChargingPower extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\ChargingPower';

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
        return [
            ID::make()->sortable(),

            Number :: make( 'charging_power' ) -> step( 0.01 ) -> readonly(),

            Text :: make( 'tariffs_power_range' ) -> readonly(),

            Text :: make( 'tariffs_daytime_range' ) -> readonly(),

            Number :: make( 'tariff_price' ) -> step( 0.01 ) -> readonly(),

            Text :: make( 'start_at' ) -> readonly(),

            Text :: make( 'end_at' ) -> readonly(),

            BelongsTo :: make('Order'),
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
     * Create policy.
     * 
     * @param Request $request
     * @return mixed
     */
    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    /**
     * Delete policy.
     * 
     * @param Request $request
     * @return mixed
     */
    public function authorizedToDelete(Request $request)
    {
        return false;
    }

    /**
     * Update policy.
     * 
     * @param Request $request
     * @return mixed
     */
    public function authorizedToUpdate(Request $request)
    {
        return false;
    }
}
