<?php

namespace Redberry\GeorgianCardGateway\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;


class PaymentController extends Controller
{
  public function succeed()
  {
    $params = request() -> all();
    Log::info([ 'payment_succeed_params' => $params ]);
  }
  
  public function failed()
  {
    $params = request() -> all();
    Log::info([ 'payment_failed_params' => $params ]);
  }
}
