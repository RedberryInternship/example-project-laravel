<?php

Route::get('/succeed', 'Redberry\GeorgianCardGateway\PaymentController@getSucceed');
Route::get('/failed', 'Redberry\GeorgianCardGateway\PaymentController@getFailed');
Route::get('/payment/{param}', 'Redberry\GeorgianCardGateway\PaymentController@getPayment');
Route::get('/test', 'Redberry\GeorgianCardGateway\PaymentController@getTest');