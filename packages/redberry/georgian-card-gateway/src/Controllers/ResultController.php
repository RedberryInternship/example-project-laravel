<?php

namespace Redberry\GeorgianCardGateway\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;


class ResultController extends Controller
{
  public function succeed()
  {
    $params = request() -> all();
    Log::info([ 'payment_succeed_params' => $params ]);
    dump( $params );
  }

  public function failed()
  {
    $params = request() -> all();
    Log::info([ 'payment_failed_params' => $params ]);
    dump( $params );
  }
}
