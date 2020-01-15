<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\DateTime;
use App\Nova\Filters\OrderFinished;
use App\Nova\Filters\OrderConfirmed;
use App\Nova\Filters\OrderRefunded;
use App\Nova\Filters\OrderRequestedAlready;
use App\Nova\Filters\OrderStatus;

class Order extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Order';

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
     * Eager Loading.
     *
     * @var string
     */
    public static $with = ['user.role', 'charger_type', 'charging_type', 'connector_type', 'charger'];

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
            BelongsTo::make('User'),
            BelongsTo::make('Charger'),
            BelongsTo::make('Connector Type'),
            BelongsTo::make('Charger Type'),
            BelongsTo::make('Charging Type'),
            Boolean::make('finished')
                ->trueValue(1)
                ->falseValue(0),
            Text::make('charge_fee'),
            Text::make('charge_time'),
            Text::make('charger_transaction_id'),
            Boolean::make('confirmed')
                ->trueValue(1)
                ->falseValue(0),
            Text::make('confirm_date'),
            Boolean::make('refunded')
                ->trueValue(1)
                ->falseValue(0),
            Text::make('price'),
            Text::make('target_price'),
            Boolean::make('requested_already')
                ->trueValue(1)
                ->falseValue(0),
            Boolean::make('status')
                ->trueValue(1)
                ->falseValue(0),
            Text::make('comment'),
            DateTime::make('created_at'),
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
        return [
            new OrderFinished,
            new OrderConfirmed,
            new OrderRefunded,
            new OrderRequestedAlready,
            new OrderStatus
        ];
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
}
