<?php

use Illuminate\Support\Facades\Route;

Route :: group([ 'prefix' => 'payment', 'namespace' => 'Redberry\GeorgianCardGateway' ], function() {
  Route :: get( 'succeed'      ,   'PaymentController@getSucceed' );
  Route :: get( 'failed'       ,   'PaymentController@getFailed' );
    
  Route :: get( 'initiation'   ,   'PaymentController@initiate' );
  Route :: get( 'avail-check'  ,   'PaymentController@paymentAvailResponse' );
  Route :: get( 'register'     ,   'PaymentController@registerPaymentResponse' );
});