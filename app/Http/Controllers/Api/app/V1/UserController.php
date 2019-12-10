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

    public function postSendSmsCode($phone_number)
    { 
        $rand        = rand(pow(10, 4-1), pow(10, 4)-1);
        $json_status = 'SMS Sent';
        $temp = TempSmsCode::where('phone_number', $phone_number) -> first();
        if($temp)
        {
            $temp -> phone_number = $phone_number;
            $temp -> code         = $rand;
            $temp -> save();
        }else{
            $temp = TempSmsCode::create([
                'phone_number' => $phone_number,
                'code'         => $rand
            ]);
        }
        return response() -> json([
            'json_status' => $json_status
        ]);
    }
    public function postVerifyCode($phone_number, $code)
    {
        $json_status  = 'Not found';
        $status       = 401;
        $temp = TempSmsCode::where(['phone_number' => $phone_number , 'code' => $code]) -> first();
        if($temp)
        {   
            $totalDuration = Carbon::now()->diffInMinutes($temp -> created_at);
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
        return response() -> json(['status' => $json_status, 'phone_number' => $phone_number], $status);
    }

    public function register(Request $request, $phone_number)
    {
        $json_status = 'Not Registered';
        $status      = 403;

        $validator = Validator::make($request->all(), [
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'email'             => 'required|string|email|max:255|unique:users'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'role'              => 1,
            'first_name'        => $request ->get('first_name'),
            'last_name'         => $request ->get('last_name'),
            'phone_number'      => $phone_number,
            'email'             => $request ->get('email'),
            'verified'          => 1,
            'acitve'            => 0,
            'password'          => Hash::make($request ->get('password'))
        ]);
        if($user)
        {
            $json_status = 'Registered';
            $status      = 200;
            $temp  = TempSmsCode::where('phone_number', $phone_number) -> delete();
        }

        $token = JWTAuth::fromUser($user);

        return response() -> json([
            'json_status' => $json_status, 
            'user'        => $user,
            'token'       => $token
        ], $status);
    }

    public function postResetPassword($phone_number, $password)
    {   
        $json_status = "User Not Found";
        $status      = 401;
        $user        = User::where('phone_number', $phone_number) -> first();

        if($user)
        {
            $user -> password = Hash::make($password);
            $user -> save();
            $temp        = TempSmsCode::where('phone_number', $phone_number) -> delete();
            $json_status = 'Password Changed';
            $status      = 200;
        }

        return response() -> json([
            'json_status' => $json_status,
        ], $status);
    }

    public function postAddUserCar(Request $request, $car_model_id)
    {
        $json_status = 'Not added!';
        $status      = 404;
        $user = auth('api') -> user();
        if($user)
        {
            $user_id = $user -> id;
            $car_model = CarModel::where('id', $car_model_id) -> first();
            if($car_model){
                $user_car_model = UserCarModel::create([
                    'user_id'  => $user_id,
                    'model_id' => $car_model_id
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

    public function getDeleteUserCar(Request $request, $user_car_id)
    {   
        $json_status = 'Not Deleted';
        $status      = 404;
        $user = auth('api') -> user();
        if($user)
        {
            $user_car    = UserCarModel::where('id', $user_car_id) -> first();
            if($user_car)
            {
                $user_car -> delete();
                $json_status = 'Deleted';
                $status      = 200;
            } 
        }
       
        return response() -> json(['json_status' => $json_status,], $status); 
    }

}

