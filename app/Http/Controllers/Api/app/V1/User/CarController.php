<?php

namespace App\Http\Controllers\Api\app\V1\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\UserCarModel;
use App\User;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User :: find( auth() -> user() -> id );

        return response() -> json([
            'user_cars' => $user -> getFormattedUserCars()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth('api') -> user();

        if( ! $request -> get('car_model_id') )
        {
            return response() -> json([
                'added' => false
            ], 422);
        }

        UserCarModel::create([
            'user_id'  => $user -> id,
            'model_id' => $request -> get('car_model_id')
        ]);

        return response() -> json([
            'added' => true
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = auth('api') -> user();

        UserCarModel::where([
            ['user_id', $user -> id],
            ['model_id', $request -> get('car_model_id')]
        ]) -> delete();

        return response() -> json([], 200);
    }
}
