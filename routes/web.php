<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Route;
use App\Library\Entities\Helper;

Route::redirect('/', Helper::novaURL());

Route::group(['prefix' => 'business', 'namespace' => 'Business'], function() {

    Route::get('/login', 'AuthController@login');
    Route::post('/auth', 'AuthController@auth');

    Route::middleware(['business.auth', 'business.language'])->group(function() {
        /**
         * Auth.
         */
        Route::get('/', 'DashboardController');
        Route::get('/logout', 'AuthController@logout');

        /**
         * Profile.
         */
        Route::get('/profile', 'ProfileController@index');
        Route::get('profile/change-language', 'ProfileController@changeLanguage')->name('business.change-language');
        Route::post('/profile', 'ProfileController@store');
        Route::get('/profile/download-contract', 'ProfileController@downloadContractFile');

        /**
         * Orders.
         */
        Route::get('/orders', 'OrderController@index')->name('business-orders.index');
        Route::get('/orders/{id}', 'OrderController@show')->name('business-orders.show');
        Route::get('/order-exports', 'OrderController@downloadExcel')->name('business-orders.downloadExcel');

        /**
         * Chargers.
         */
        Route::get('/chargers', 'ChargerController@index');
        Route::get('/chargers/{id}/edit', 'ChargerController@edit')->name('business-chargers.edit');
        Route::post('/chargers/{id}/update', 'ChargerController@update');
        Route::post('/filter-chargers', 'ChargerController@getFilteredChargers');
        Route::post('/charger-transfer', 'ChargerTransferController');

        /**
         * Groups.
         */
        Route::resource('/groups', 'GroupController')->only(['index', 'store', 'edit', 'destroy']);
        Route::delete('/groups/charging-prices/delete', 'GroupController@deleteChargingPrices')->name('groups.delete-all-charging-prices');
        Route::post('/groups/store/all', 'GroupController@storeAllChargersToGroup')->name('groups.store-all-charging-prices');

        Route::resource('/group-prices', 'GroupPriceController')->only(['show', 'update']);
        Route::resource('/group-fast-prices', 'GroupFastPriceController')->only(['show', 'update']);

        /**
         * Charging Prices.
         */
        Route::resource('/charging-prices', 'ChargingPricesController')->only(['store', 'destroy', 'edit', 'update']);
        Route::resource('/fast-charging-prices', 'FastChargingPricesController')->only(['store', 'destroy', 'edit', 'update']);

        /** Whitelist api */
        Route::post('/chargers/toggle-visibility', 'WhitelistController@toggleHiddenField');
        Route::get('/chargers/{charger_id}/whitelist', 'WhitelistController@getWhitelist');
        Route::post('/chargers/add/whitelist', 'WhitelistController@addToWhitelist');
        Route::post('/chargers/remove-from/whitelist', 'WhitelistController@removeFromWhitelist');

        /**
         * Analytics.
         */
        Route::group(['prefix' => 'analytics'], function() {
            Route::get('/income', 'AnalyticsController@incomeAndExpense');
            Route::get('/transactions', 'AnalyticsController@businessTransactionsAndWastedEnergy');
            Route::get('/top-chargers', 'AnalyticsController@topChargers');
            Route::get('/charger-statuses', 'AnalyticsController@businessChargerStatuses');
        });
    });

});

/**
 * Real chargers services.
 */
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
