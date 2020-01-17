<?php

namespace App\Http\Controllers\Api\app\V1;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\TempSmsCode;
use Carbon\Carbon;
use App\CarModel;
use App\UserCarModel;

use Schema;

class UserController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('phone_number', 'password');
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'User Not Found', 'status' => 401], 401);
        }
        return $this->respondWithToken($token);
    }
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
            'user'          => $this->guard()->user(),
            'token_type'    => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL()
        ]);
    }

    public function postSendSmsCode(Request $request)
    { 
        //$rand        = rand(pow(10, 4-1), pow(10, 4)-1);
        $rand        = 3030;
        $json_status = 'SMS Sent';
        $temp = TempSmsCode::where('phone_number', $request -> get('phone_number')) -> first();
        if($temp)
        {
            $temp -> phone_number = $request -> get('phone_number');
            $temp -> code         = $rand;
            $temp -> updated_at   = Carbon::now();
            $temp -> save();
        }else{
            $temp = TempSmsCode::create([
                'phone_number' => $request -> get('phone_number'),
                'code'         => $rand
            ]);
        }
        return response() -> json([
            'json_status' => $json_status
        ]);
    }
    public function postVerifyCode(Request $request)
    {
        $json_status  = 'Not found';
        $status       = 401;
        $temp = TempSmsCode::where([
            'phone_number' => $request -> get('phone_number'), 
            'code'         => $request -> get('code')
        ]) -> first();
        if($temp)
        {   
            $totalDuration = Carbon::now()->diffInMinutes($temp -> updated_at);
            if($totalDuration <= 3)
            {
                $temp -> status = 1;
                $temp -> save();
                $json_status = 'Verified';
                $status      = 200;
            }else{
                $json_status = "SMS Code Expired";
                $status      = 440;
            }
        }
        return response() -> json([
            'json_status'  => $json_status,
            'status'       => $status,
            'phone_number' => $request -> get('phone_number')
        ], $status);
    }

    public function register(Request $request)
    {
        $json_status = 'Not Registered';
        $status      = 403;

        $validator = Validator::make($request->all(), [
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'email'             => 'required|string|email|max:255|unique:users',
            'phone_number'      => 'required|string|unique:users'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'role'              => 1,
            'first_name'        => $request ->get('first_name'),
            'last_name'         => $request ->get('last_name'),
            'phone_number'      => $request ->get('phone_number'),
            'email'             => $request ->get('email'),
            'verified'          => 1,
            'acitve'            => 0,
            'password'          => Hash::make($request ->get('password'))
        ]);

        if($user)
        {
            $json_status = 'Registered';
            $status      = 200;
            $temp  = TempSmsCode::where('phone_number', $request ->get('phone_number')) -> delete();
        }

        $token = JWTAuth::fromUser($user);

        return response() -> json([
            'json_status' => $json_status, 
            'user'        => $user,
            'token'       => $token
        ], $status);
    }

    public function postResetPassword(Request $request)
    {   
        $json_status = "User Not Found";
        $status      = 401;
        $user        = User::where('phone_number', $request -> phone_number) -> first();
        if($user)
        {
            $user -> password = Hash::make($request -> password);
            $user -> save();
            $temp        = TempSmsCode::where('phone_number', $request -> phone_number) -> delete();
            $json_status = 'Password Changed';
            $status      = 200;
        }

        return response() -> json([
            'json_status' => $json_status,
        ], $status);
    }

    public function postAddUserCar(Request $request)
    {
        $json_status = 'Not added!';
        $status      = 404;
        $user = auth('api') -> user();
        if($user)
        {
            $user_id = $user -> id;
            $car_model = CarModel::where('id', $request -> get('car_model_id')) -> first();
            if($car_model){
                $user_car_model = UserCarModel::create([
                    'user_id'  => $user_id,
                    'model_id' => $request -> get('car_model_id')
                ]);
                $json_status = 'User Car Added';
                $status      = 200;
            }else{
                $json_status = 'Car Model Not Found';
                $status      = 401;
            }
        }else{
            $json_status = "User Not Found";
            $status      = 401;
        }
        return response() -> json([
            'json_status' => $json_status,
        ], $status);
    }

    public function getUserCars(Request $request)
    {   
        $user_cars = [];
        $user = auth('api') -> user();
        if($user)
        {
            if($user -> car_models)
            {
                foreach($user -> car_models as $user_car_model)
                {   
                    $mark_id     = $user_car_model -> mark -> id;
                    $mark_name   = $user_car_model -> mark -> name;
                    $model_id    = $user_car_model -> id;
                    $model_name  = $user_car_model -> name;

                    $user_cars[] = array(
                        'user_id' => $user -> id,
                        'user_car' => array(
                            'mark_id'    => $mark_id,
                            'mark_name'  => $mark_name,
                            'model_id'   => $model_id,
                            'model_name' => $model_name
                        ),
                    );
                    $json_status = 'Success';
                    $status      = 200;
                }
            }
        }else{
            $json_status = 'Car Model Not Found';
            $status      = 401;
        }
        return response() -> json(['user_cars' => $user_cars, 'json_status' => $json_status], $status);
    }

    public function postDeleteUserCar(Request $request)
    {   
        $json_status = 'Not Deleted';
        $status      = 404;
        $user = auth('api') -> user();
        if($user)
        {
            $user_car    = UserCarModel::where([
                'user_id', $user -> id,
                'model_id', $request -> get('model_id')
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

}

