<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\BelongsTo;
use Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel;


class Payment extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Payment';

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
     * Grouping nova resource.
     */
    public static $group = 'User Resources';

    /**
     * Eager Loading.
     *
     * @var string
     */
    public static $with = ['order', 'user_card.user'];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()
                ->sortable(),

            BelongsTo::make('Order'),

            Text::make('price'),

            Text::make('prrn'),

            Text::make('trx_id'),

            Boolean::make('confirmed'),

            Text::make('confirm_date'),

            Text::make('date'),

            BelongsTo::make('User Card')
                ->displayUsing(function($card) {
                    return
                        $card -> user -> first_name . ' ' .
                        $card -> user -> last_name . ' - ' .
                        $card -> masked_pan;
                }),

            DateTime::make('Created At')
                ->readonly(),
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
        return [
            (new DownloadExcel) -> withHeadings(),
        ];
    }
}
