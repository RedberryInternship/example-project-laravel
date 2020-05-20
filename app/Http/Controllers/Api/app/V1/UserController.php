<?php

namespace App\Http\Controllers\Api\app\V1;

use Twilio;
use App\User;
use App\Order;
use App\Charger;
use App\CarModel;
use App\UserCarModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChargerCollection;
use App\Http\Resources\OrdersCollection;
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
        
        $json_status = 'Success';
        $status      = 200;
        
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

        if ( ! $user -> active || ! $user -> verified)
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

    public function getOrders(Order $order)
    {
        $user = auth('api') -> user();

        return new OrdersCollection(
            $order
                -> where('user_id', $user -> id)
                -> with('charger_connector_type.charger')
                -> confirmedPaymentsWithUserCards()
                -> get()
        );
    }

    public function getUserChargers(Charger $charger, $quantity = 3)
    {
        $chargerModel = new Charger();
        $user = auth('api') -> user();
        $favoriteChargers = $user -> favorites -> pluck('id') -> toArray();

        $chargers = $charger -> whereHas('charger_connector_types.orders', function($query) use ($user) {
            return $query -> where('user_id', $user -> id);
        })
        -> withAllAttributes()
        -> orderBy('id', 'DESC')
        -> take($quantity)
        -> get();

        $chargerModel -> addFilterAttributeToChargers($chargers, $favoriteChargers);

        Charger::addIsFreeAttributeToChargers($chargers);

        return new ChargerCollection($chargers);
    }

    public function testTwilio()
    {
        Twilio::message('+995598980526', 'Hello from twilio');
    }
}
