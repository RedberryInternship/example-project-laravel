<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'E2E', 'prefix' => 'e2e'], function () {
  Route::group(['prefix' => 'user'], function() {
    Route::delete('/', 'UserController@destroy');
    Route::patch('/reset-password', 'UserController@resetPassword');
    Route::get('/otp', 'UserController@getUserOTP');
    Route::delete('/clear-favorites', 'UserController@clearFavorites');
  });
});