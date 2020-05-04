<?php
use Illuminate\Support\Facades\Route;
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

	/* User Auth / Register */
	Route::group(['namespace' => 'Api\app\V1'], function() {
		Route::post('/verify-code-for-password-recovery','UserController@postVerifyCodeForPasswordRecovery');
		Route::post('/reset-password', 'UserController@postResetPassword');
		Route::post('/edit-password', 'UserController@postEditPassword');
		

		Route::post('/send-sms-code','User\CodeController@sendCode');
		Route::post('/verify-code','User\CodeController@verifyCode');
		Route::post('/login', 'User\AuthController');
		Route::post('/register', 'User\RegistrationController');
	});


	/* User Authenticated use functionality */
	Route::group(['middleware' => ['jwt.verify']], function() {
		Route::group(['namespace' => 'Api\app\V1'], function(){
			Route::post('/add-user-car', 'UserController@postAddUserCar');
			Route::get('/get-user-cars' , 'UserController@getUserCars');
			Route::post('/delete-user-car', 'UserController@postDeleteUserCar');
			Route::post('/add-favorite', 'FavoriteController@postAddFavorite');
			Route::post('/remove-favorite', 'FavoriteController@postRemoveFavotite');
			Route::get('/user-favorites', 'FavoriteController@getUserFavorites');
			Route::get('/user-orders', 'UserController@getOrders');
			Route::get('/user-chargers/{quantity?}', 'UserController@getUserChargers');
			Route::post('/update-user-info','UserController@postUpdateUserInfo');
			Route::post('/update-user-info','UserController@postUpdateUserInfo');
			Route::get('/me', 'UserController@getMe');

			Route::group(['namespace' => 'Chargers'], function() {
				Route::post('/charging/start', 'ChargingController@start');
				Route::post('/charging/stop', 'ChargingController@stop');
				Route::get('/charging/status/{charger_connector_type_id}', 'StatusController@getChargingStatus');
			});

		});
	});

	/* Rest functionality */
	Route::group(['namespace' => 'Api\app\V1'], function(){
		Route::get('/charger/{charger_id}', 'ChargerController@getSingleCharger');
		Route::get('/chargers', 'ChargerController@getChargers');
		Route::get('/get-models-and-marks', 'GetModelsAndMarksController@getModelsAndMarks');
		Route::get('/phone-codes' , 'PhoneCodesController@getPhoneCodes');
		Route::get('/geo-ip', 'LocationController@getLocation');
		Route::get('/faq', 'FAQController');
		Route::get('/partners', 'PartnerController');
	});
});	
