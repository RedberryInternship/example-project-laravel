<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Route;
use App\Helpers\App;

Route::get('/', function () {
    return redirect('http://e-space.ge');
});

Route::group(['prefix' => 'business', 'namespace' => 'Business'], function() {
    Route::get('/', 'DashboardController');
    Route::get('/login', 'AuthController@login');
    Route::post('/auth', 'AuthController@auth');
    Route::get('/logout', 'AuthController@logout');
    Route::post('/charger-transfer', 'ChargerTransferController');

    Route::resource('/orders', 'OrderController');
    Route::resource('/profile', 'ProfileController');
    Route::resource('/chargers', 'ChargerController');
    Route::resource('/groups', 'GroupController');
    Route::resource('/group-prices', 'GroupPriceController');
    Route::resource('/group-fast-prices', 'GroupFastPriceController');
    Route::resource('/charging-prices', 'ChargingPricesController');
    Route::resource('/fast-charging-prices', 'FastChargingPricesController');

    Route::group(['prefix' => 'analytics', 'namespace' => 'Analytics'], function() {
        Route::get('/income', 'IncomeController');
        Route::get('/transactions', 'TransactionsController');
        Route::get('/active-chargers', 'ActiveChargersController');
        Route::get('/charger-statuses', 'ChargerStatusesController');
    });

    Route::group(['prefix' => 'exports', 'namespace' => 'Exports'], function() {
        Route::get('/orders', 'OrderController');
    });
});

Route::group(['namespace' => 'Api\ChargerTransactions\V1', 'prefix' => 'chargers/transactions'], function(){
    Route::get('finish/{transaction_id}',           'TransactionController@finish');
    Route::get('update/{transaction_id}/{value}',   'TransactionController@update');
});

Route::post('refund', 'TestController@doRefund') -> name('refund');

/**
 * Testing routes for development purposes
 */
 if( App :: dev() )
 {
    Route::get('test'   , 'TestController');
    Route::get('/disconnect' , 'TestController@disconnect');
    Route::post('/disconnect', 'TestController@disconnect');
    
    Route::get('/test-twilio', 'Api\app\V1\UserController@testTwilio');
            
    Route::get('firebase', 'TestController@firebase');
    Route::get('refund', 'TestController@refundView');
 }
