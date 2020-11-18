<?php

use Illuminate\Support\Facades\Route;

$routeGroupParams = [
  'prefix'    => 'payment',
  'namespace' => 'Redberry\GeorgianCardGateway\Controllers'
];

Route :: group( $routeGroupParams, function() {
  Route :: get( 'avail-check'  ,   'ResponseController@paymentAvailResponse' );
  Route :: get( 'register'     ,   'ResponseController@registerPaymentResponse' );

  Route :: get( 'succeed'      ,   'ResultController@succeed' );
  Route :: get( 'failed'       ,   'ResultController@failed' );
});
