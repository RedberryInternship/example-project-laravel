<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'E2E', 'prefix' => 'e2e'], function () {
  Route::delete('user', 'UserController@destroy');
  Route::get('user/otp', 'UserController@getUserOTP');
});