<?php


namespace App\Http\Controllers\Api\ChargerTransactions\V1;

use App\Http\Controllers\Controller;
use Log;

class TransactionController extends Controller
{

  public function finish($transaction_id)
  {
    Log::channel('transaction_stop')->info([
      'transaction_id' => $transaction_id,
      'additional_data' => request() -> all(),
    ]);
  }

  public function update($transaction_id, $value)
  {
    Log::channel('transaction_update')->info(
      [
        'transaction_id' => $transaction_id, 
        'value' => $value,
        'additional_data' => request() -> all(),
        ]
      );
  }
}
