<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'E2E', 'prefix' => 'e2e'], function () {
  Route::group(['prefix' => 'user'], function() {
    Route::delete('/', 'UserController@destroy');
    Route::patch('/reset-password', 'UserController@resetPassword');
    Route::get('/otp', 'UserController@getUserOTP');
    Route::put('/reset-data', 'UserController@resetData');
  });

  Route::delete('/clear-favorites', 'ClearFavoritesController');
  Route::delete('/clear-cars', 'ClearCarsController');
});