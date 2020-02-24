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
	Route::post('/verify-code-for-password-recovery','Api\app\V1\UserController@postVerifyCodeForPasswordRecovery');
	Route::post('/register', 'Api\app\V1\UserController@register');
	Route::post('/reset-password', 'Api\app\V1\UserController@postResetPassword');
	Route::post('/edit-password', 'Api\app\V1\UserController@postEditPassword');

	Route::group(['middleware' => ['jwt.verify']], function() {
		Route::post('/add-user-car', 'Api\app\V1\UserController@postAddUserCar');
		Route::get('/get-user-cars' , 'Api\app\V1\UserController@getUserCars');
		Route::post('/delete-user-car', 'Api\app\V1\UserController@postDeleteUserCar');
		Route::post('/add-favorite', 'Api\app\V1\FavoriteController@postAddFavorite');
		Route::post('/remove-favorite', 'Api\app\V1\FavoriteController@postRemoveFavotite');
		Route::get('/user-favorites', 'Api\app\V1\FavoriteController@getUserFavorites');
		Route::post('/update-user-info','Api\app\V1\UserController@postUpdateUserInfo');
		Route::post('/update-user-info','Api\app\V1\UserController@postUpdateUserInfo');
		Route::post('/me', 'Api\app\V1\UserController@postMe');
	});

	Route::get('/charger/{charger_id}', 'Api\app\V1\ChargerController@getSingleCharger');
	Route::get('/chargers', 'Api\app\V1\ChargerController@getChargers');
	Route::get('/get-models-and-marks', 'Api\app\V1\GetModelsAndMarksController@getModelsAndMarks');
	Route::get('/phone-codes' , 'Api\app\V1\PhoneCodesController@getPhoneCodes');
	Route::get('/geo-ip', 'Api\app\V1\LocationController@getLocation');
});	
