<?php

namespace App\Nova;

use App\User;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Spatie\Translatable\HasTranslations;
use Spatie\NovaTranslatable\Translatable;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Select;
use App\Nova\Filters\ChargerPublished;
use App\Nova\Filters\ChargerActive;
use App\Nova\Filters\ChargerTags;
//use App\Nova\Filters\ChargerTypes;
use App\Nova\Filters\ChargerConnectorTypes;

class Charger extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Charger';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name'
    ];

    /**
     * Eager Loading.
     *
     * @var string
     */
    public static $with = [];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $users = User::where('role_id', 3) -> get() -> keyBy('id') -> map(function($u) {
            return $u -> first_name . ' ' . $u -> last_name;
        }) -> toArray();

        $fieldsArr = [
            ID::make()->sortable(),

            Translatable::make([
                Text::make('name')
                    ->sortable()
                    ->rules('max:255')
            ])->locales(['en', 'ru', 'ka']),

            ID::make('charger_id')
                ->sortable(),

            Text::make('code'),

            Translatable::make([
                Text::make('description')
                    ->sortable()
            ])->locales(['en', 'ru', 'ka']),

            Translatable::make([
                Text::make('location')
                    ->sortable()
                    // ->rules('required', 'max:255')
            ])->locales(['en', 'ru', 'ka']),

            Boolean::make('public')
                ->trueValue(1)
                ->falseValue(0),

            Boolean::make('active')
                ->trueValue(1)
                ->falseValue(0),

            Text::make('lat'),

            Text::make('lng'),

            // BelongsTo::make('User','user', 'App\Nova\User')
            //     ->onlyOnForms()
            //     ->nullable(),

            BelongsToMany::make('Connector Types')
                -> fields(function () {
                    return [
                        Text::make('charger_type_id') -> onlyOnIndex(),
                        Select::make('charger_type_id') -> options(ChargerType::getChargerTypesKeyValueArray())
                    ];
                }),
            // Text::make('Types') -> displayUsing(function ($types){
            //     $result = "";
            //     $i = 0;
            //     $len = count($types);
            //     foreach ($types as $type)
            //     {
            //         $result .= $type -> name;
            //         if ($i == $len - 1)
            //         {
            //             $result .= ". ";
            //         } else {
            //             $result .= ", ";
            //         }
            //         $i++;
            //     }
            //     return $result;
            // })-> onlyOnIndex(),

            BelongsToMany::make('Charger Tags','Tags', 'App\Nova\Tag'),
            Select::make('User','user_id')
                ->options($users),
            BelongsTo::make('charger_group')
                -> nullable()
        ];

        return $fieldsArr;
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
            new ChargerPublished,
            new ChargerActive,
            new ChargerTags,
            //new ChargerTypes,
            new ChargerConnectorTypes
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
