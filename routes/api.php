<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::group(['prefix' => 'app/V1'], function () {
	Route::post('/login', 'Api\app\V1\UserController@authenticate');
	Route::post('/send-sms-code','Api\app\V1\UserController@postSendSmsCode');
	Route::post('/verify-code','Api\app\V1\UserController@postVerifyCode');
	Route::post('/register', 'Api\app\V1\UserController@register');
	Route::post('/reset-password', 'Api\app\V1\UserController@postResetPassword');
	Route::post('/add-user-car', 'Api\app\V1\UserController@postAddUserCar');
	Route::get('/get-delete-user-car/{car_model_id}', 'Api\app\V1\UserController@getDeleteUserCar');
	Route::get('/get-user-cars' , 'Api\app\V1\UserController@getUserCars');

	// Route::group(['middleware' => ['jwt.verify']], function() {
	// 	Route::get('/me', 'Api\app\V1\UserController@getMe');
	// });
	
	Route::get('/get-charger/{charger_id}', 'Api\app\V1\ChargerController@getSingleCharger');
	Route::get('/get-chargers', 'Api\app\V1\ChargerController@getChargers');
	Route::get('/get-models-and-marks', 'Api\app\V1\GetModelsAndMarksController@getModelsAndMarks');
});	
