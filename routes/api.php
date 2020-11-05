<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'app/V1'], function () {
	/* User Auth/Register */
	Route::group(['namespace' => 'Api\app\V1'], function () {
		Route::post('/send-sms-code', 'User\CodeController@sendCode');
		Route::post('/verify-code', 'User\CodeController@verifyCode');
		Route::post('/verify-code-for-password-recovery', 'User\CodeController@verifyCodeForPasswordRecovery');

		Route::post('/reset-password', 'User\PasswordController@reset');
		Route::post('/edit-password', 'User\PasswordController@edit');

		Route::post('/login', 'User\AuthController');
		Route::post('/register', 'User\RegistrationController');
	});

	/* User Authenticated use functionality */
	Route::group(['middleware' => ['jwt.verify', 'check.user.existence']], function () {
		Route::group(['namespace' => 'Api\app\V1'], function () {
			Route::get('/get-user-cars', 'User\CarController@index');
			Route::post('/add-user-car', 'User\CarController@store');
			Route::post('/delete-user-car', 'User\CarController@destroy'); //todo Vobi, რესტის სტანდართით ეს delete უნდა იყოს

			Route::post('/add-favorite', 'FavoriteController@postAddFavorite');
			Route::post('/remove-favorite', 'FavoriteController@postRemoveFavotite');
			Route::get('/user-favorites', 'FavoriteController@getUserFavorites');
			Route::get('/transactions-history', 'User\TransactionController');
			Route::get('/user-chargers/{quantity?}', 'User\ChargerController');
			Route::post('/update-user-info', 'UserController@postUpdateUserInfo'); //todo Vobi, რესტის სტანდართით ეს put უნდა იყოს
			Route::post('/update-firebase-token', 'User\FirebaseTokenController@update'); //todo Vobi, რესტის სტანდართით ეს put უნდა იყოს
			Route::get('/me', 'UserController@getMe');

			/** Charging */
			Route::post('/charging/start', 'ChargingController@start');
			Route::post('/charging/stop', 'ChargingController@stop');

			/** Orders */
			Route::get('/active-orders', 'OrderController@getActiveOrders');
			Route::get('/order/{id}', 'OrderController@get');

			/** UserCards */
			Route::get('/save-card-url', 'UserCardController@getSaveCardUrl');
			Route::post('/user-card/set-default', 'UserCardController@setDefaultUserCard');
			Route::post('/user-card/remove-card', 'UserCardController@removeUserCard');
		});
	});

	/* Rest functionality */
	Route::group(['namespace' => 'Api\app\V1'], function () {
		Route::get('/charger/{charger_id}', 'ChargerController@getSingleCharger');
		Route::get('/chargers', 'ChargerController@getChargers');
		Route::get('/get-models-and-marks', 'GetModelsAndMarksController@getModelsAndMarks');
		Route::get('/phone-codes', 'PhoneCodesController@getPhoneCodes');
		Route::get('/geo-ip', 'LocationController@getLocation');
		Route::get('/faq', 'FAQController');
		Route::get('/partners', 'PartnerController');
		Route::post('/contact-message', 'ContactMessageController');
		Route::get('/contact', 'ContactController');
	});
});
