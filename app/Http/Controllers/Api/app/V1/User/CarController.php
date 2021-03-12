<?php

namespace App\Http\Controllers\Api\app\V1\User;

use App\Http\Requests\User\StoreCarRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\UserCarModel;
use App\User;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $user = User :: find( auth() -> user() -> id );

        return response() -> json(
            [
                'user_cars' => $user -> getFormattedUserCars()
            ], 
            200,
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(StoreCarRequest $request)
    {
        $user = auth('api') -> user();

        $carAttributes = [
            'user_id'  => $user -> id,
            'model_id' => $request -> get('car_model_id')
        ];

        UserCarModel :: create($carAttributes);

        return response() -> json(
            [
                'added' => true,
            ], 
            200,
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy(Request $request)
    {
        $request->validate(
            [
                'car_model_id' => 'required',
            ]
        );

        $user = auth('api') -> user();

        UserCarModel::where(
            [
                ['user_id', $user -> id],
                ['model_id', $request -> get('car_model_id')]
            ]
        ) -> delete();

        return response() -> json([], 200);
    }
}
