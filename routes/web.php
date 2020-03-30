<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'business'], function(){
    Route::get('/', 'BusinessController@getIndex');
    Route::get('/login', 'BusinessController@getLogin');
    Route::get('/register', 'BusinessController@getRegister');
    Route::get('/forgot-password', 'BusinessController@getForgotPassword');
    Route::post('/register', 'UserController@postRegister');
    Route::post('/login', 'UserController@postAuthenticate');
    Route::get('/logout', 'UserController@getLogout');
    Route::get('/chargers', 'ChargerController@getChargers');
    Route::get('/charger-services/{charger_id}', 'ChargerController@getChargerServices');
    Route::post('/add-charger-bussiness-service', 'ChargerController@postChargerBusinessService');
    Route::get('/delete-charger-business-service/{charger_business_service_id}', 'ChargerController@getDeleteChargerBusinessService');
    Route::get('/business-services', 'ChargerBusinessServiceController@getBusinessServices');
    Route::get('/add-business-service', 'ChargerBusinessServiceController@getAddBusinessService');
    Route::post('/add-business-service', 'ChargerBusinessServiceController@postAddBusinessService');
    Route::get('/delete-business-service/{service_id}', 'ChargerBusinessServiceController@getDeleteBusinessService');
});

Route::group(['namespace' => 'Api\ChargerTransactions\V1', 'prefix' => 'chargers/transactions'], function(){
    Route::get('finish/{transaction_id}','TransactionController@finish');
    Route::get('update/{transaction_id}/{value}','TransactionController@update');
});

Route::get('/test-twilio', 'Api\app\V1\UserController@testTwilio');

Route::get('test','TestController@index');

