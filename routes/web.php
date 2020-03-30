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

Route::group(['prefix' => 'business'], function() {
    Route::get('/', 'BusinessController@getIndex');
    Route::get('/login', 'BusinessController@getLogin');
    Route::get('/register', 'BusinessController@getRegister');
    Route::get('/forgot-password', 'BusinessController@getForgotPassword');
    Route::post('/register', 'UserController@postRegister');
    Route::post('/login', 'UserController@postAuthenticate');
    Route::get('/logout', 'UserController@getLogout');
    Route::get('/charger-groups', 'BusinessController@getChargerGroups');

    Route::resource('/chargers', 'Business\ChargerController');
});