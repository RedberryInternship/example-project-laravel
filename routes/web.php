<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Route;
use App\Library\Entities\Helper;

Route::redirect('/', 'http://app.e-space.ge/nova');

Route::group(['prefix' => 'business', 'namespace' => 'Business'], function() {
    Route::get('/', 'DashboardController');
    Route::get('/login', 'AuthController@login');
    Route::post('/auth', 'AuthController@auth');
    Route::get('/logout', 'AuthController@logout');
    Route::post('/charger-transfer', 'ChargerTransferController');

    Route::resource('/orders', 'OrderController');
    Route::get('/orders/{id}', 'OrderController@show');
    Route::get('/order-exports', 'OrderController@downloadExcel');
    Route::get('/profile/download-contract', 'ProfileController@downloadContractFile');
    Route::resource('/profile', 'ProfileController');
    Route::resource('/chargers', 'ChargerController');
    Route::post('/filter-chargers', 'ChargerController@getFilteredChargers');
    Route::resource('/groups', 'GroupController');
    Route::delete('/groups/charging-prices/delete', 'GroupController@deleteChargingPrices');
    Route::post('/groups/store/all', 'GroupController@storeAllChargersToGroup');
    Route::resource('/group-prices', 'GroupPriceController');
    Route::resource('/group-fast-prices', 'GroupFastPriceController');
    Route::resource('/charging-prices', 'ChargingPricesController');
    Route::resource('/fast-charging-prices', 'FastChargingPricesController');

    /** Whitelist api */
    Route::post('/chargers/toggle-visibility', 'WhitelistController@toggleHiddenField');
    Route::get('/chargers/{charger_id}/whitelist', 'WhitelistController@getWhitelist');
    Route::post('/chargers/add/whitelist', 'WhitelistController@addToWhitelist');
    Route::post('/chargers/remove-from/whitelist', 'WhitelistController@removeFromWhitelist');

    Route::group(['prefix' => 'analytics'], function() {
        Route::get('/income', 'AnalyticsController@incomeAndExpense');
        Route::get('/transactions', 'AnalyticsController@businessTransactionsAndWastedEnergy');
        Route::get('/top-chargers', 'AnalyticsController@topChargers');
        Route::get('/charger-statuses', 'AnalyticsController@businessChargerStatuses');
    });
});

Route::group(['namespace' => 'Api\ChargerTransactions\V1', 'prefix' => 'chargers/transactions'], function(){
    Route::get('finish/{transaction_id}',           'TransactionController@finish');
    Route::get('update/{transaction_id}/{value}',   'TransactionController@update');
});

Route::post('refund', 'TestController@doRefund') -> name('refund');
Route::get('refund', 'TestController@refundView');

/**
 * Testing routes for development purposes
 */
 if( Helper :: isDev() )
 {
    Route::get('test'   , 'TestController');
    Route::get('/disconnect' , 'TestController@disconnect');
    Route::post('/disconnect', 'TestController@disconnect');

    Route::get('/test-twilio', 'Api\app\V1\UserController@testTwilio');
 }
