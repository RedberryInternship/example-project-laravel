<?php

use Illuminate\Support\Facades\Route;

$routeGroupParams = [ 
  'prefix'    => 'payment', 
  'namespace' => 'Redberry\GeorgianCardGateway\Controllers' 
];

Route :: group( $routeGroupParams, function() { 
  Route :: get( 'avail-check'  ,   'PaymentController@paymentAvailResponse' );
  Route :: get( 'register'     ,   'PaymentController@registerPaymentResponse' );

  Route :: get( 'succeed'      ,   'ResultController@succeed' );
  Route :: get( 'failed'       ,   'ResultController@failed' );
});