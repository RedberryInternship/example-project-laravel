<?php

namespace App\Http\Controllers\Api\app\V1\User;

use App\CarModel;
use App\UserCarModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth('api') -> user();

        return response() -> json([
            'user_cars' => $user -> getFormatedUserCars()
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

        UserCarModel::create([
            'user_id'  => $user -> id,
            'model_id' => $request -> get('car_model_id')
        ]);

        return response() -> json([], 200);
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
