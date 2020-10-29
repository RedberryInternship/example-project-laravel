<?php

use Illuminate\Support\Facades\Route;

$routeGroupParams = [
  'prefix'    => 'payment',
  'namespace' => 'Redberry\GeorgianCardGateway\Controllers'
];
//todo Vobi, ესეთი მძიმეების გასწორება ვერ არის სწორი ამბავი, ეხ კიდე რომ რაიმე გრძელის სახელით მოგიწიოს უნდა ურტყა პრაბელს ხელი სანამ ამას არ გაასწორებ?
// აიღე რომელიმე php style გაიდი და ყველა ფაილში იგივე სტანდარტი დაიცავი რომელიც შნახვაზე ავტომატურად დაგიმახსვორებს და შეგისწორებს. და არა ხელით ესე წვალება<div class=""></div>
Route :: group( $routeGroupParams, function() {
  Route :: get( 'avail-check'  ,   'ResponseController@paymentAvailResponse' );
  Route :: get( 'register'     ,   'ResponseController@registerPaymentResponse' );

  Route :: get( 'succeed'      ,   'ResultController@succeed' );
  Route :: get( 'failed'       ,   'ResultController@failed' );
});
