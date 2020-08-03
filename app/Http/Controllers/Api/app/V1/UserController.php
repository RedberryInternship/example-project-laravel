<?php

namespace App\Http\Controllers\Api\app\V1;

use Twilio;
use App\User;
use App\CarModel;
use App\UserCarModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;

class UserController extends Controller
{
    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function guard()
    {
        return Auth::Guard('api');
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token'  => $token,
            'user'          => $this->guard()->user()->load('user_cards','user_cars','car_models'),
            'token_type'    => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL()
        ]);
    }

    public function postDeleteUserCar(Request $request)
    {
        $json_status = 'Not Deleted';
        $status      = 404;
        $user = auth('api') -> user();
        if($user)
        {
            $user_car    = UserCarModel::where([
                ['user_id', $user -> id],
                ['model_id', $request -> get('car_model_id')],
            ]) -> first();
            if($user_car)
            {
                $user_car -> delete();
                $json_status = 'Deleted';
                $status      = 200;
            } 
        }
        return response() -> json(['json_status' => $json_status,], $status); 
    }

    public function postUpdateUserInfo(Request $request)
    {
        $user       = auth('api') -> user();
        $user_db    = User::where('id',$user -> id) -> first();
        $checker    = false;        
        $columns    = Schema::getColumnListing('users');
        $keys       = [];

        foreach($columns as $v){
            array_push($keys, $v);
        }

        if($user){
            foreach($request->all() as $key => $value){
                if(in_array($key,$keys)){
                    if($value != '' or $value != null){
                        $user_db -> update([$key => $value]);
                        $checker = true;                      
                    }
                }
            }
        }

        return response() -> json(['updated' => $checker]);
    }

    public function getMe()
    {
        $user = auth('api') -> user();

        if ( ! $user || ! $user -> active || ! $user -> verified)
        {
            return response() -> json(['error' => 'User Not Active'], 406);
        }

        if (strtolower($user -> role -> name) != 'regular')
        {
            return response() -> json(['error' => 'User Role mismatch'], 403);
        }

        $user -> load('user_cards', 'user_cars', 'car_models');

        return response() -> json($user);
    }

    public function testTwilio()
    {
        Twilio::message('+995598980526', 'Hello from twilio');
    }
}
