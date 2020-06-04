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

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'business', 'namespace' => 'Business'], function() {
    Route::get('/', 'DashboardController');
    Route::get('/login', 'AuthController@login');
    Route::post('/auth', 'AuthController@auth');
    Route::get('/logout', 'AuthController@logout');
    Route::post('/charger-transfer', 'ChargerTransferController');

    Route::resource('/chargers', 'ChargerController');
    Route::resource('/charger-groups', 'ChargerGroupController');
    Route::resource('/charging-prices', 'ChargingPricesController');
    Route::resource('/fast-charging-prices', 'FastChargingPricesController');
});

Route::group(['namespace' => 'Api\ChargerTransactions\V1', 'prefix' => 'chargers/transactions'], function(){
    Route::get('finish/{transaction_id}','TransactionController@finish');
    Route::get('update/{transaction_id}/{value}','TransactionController@update');
});

Route::group(['prefix' => 'chargers_back'], function() {
    Route::get('start-charging/{charger_id}/{connector_id}', 'TestController@start');
    Route::get('stop-charging/{charger_id}/{transactionID}', 'TestController@stop');
    Route::get('transaction-info/{transaction_id}',          'TestController@transactionInfo');
    Route::get('find/{charger_id}',                          'TestController@find');
    Route::get('all',                                        'TestController@all');

    Route::get('charger/{charger_id}/switch-to-lvl2',        'TestController@switchChargerIntoLvl2');
    Route::get('charger/{charger_id}/bring-online',          'TestController@bringChargerOnline');
    Route::get('charger/{charger_id}/plug-off',              'TestController@plugOffChargerConnectorCable');
    Route::get('charger/{charger_id}/shutdown',              'TestController@shutdown');

});

Route::get('/disconnect', 'TestController@disconnect');
Route::post('/disconnect', 'TestController@disconnect');

Route::get('/test-twilio', 'Api\app\V1\UserController@testTwilio');
        
Route::get('test','TestController');


Route::get('/test/pay/ru', function(){
    return view( 'payments.ru' );
});

Route::get('/test/pay/en', function(){
    return view( 'payments.en' );
});

Route::get('/test/pay/ge', function(){
    return view( 'payments.ge' );
});