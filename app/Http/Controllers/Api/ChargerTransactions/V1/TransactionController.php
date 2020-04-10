<?php


namespace App\Http\Controllers\Api\ChargerTransactions\V1;

use App\ChargerTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
  /**
   * Misha's route for letting us know when 
   * the charging is completed and 
   * the cable is disconnected.
   * 
   * @param string $transaction_id
   * @return void
   */
  public function finish($transaction_id)
  {
    $this -> logFinish($transaction_id);    
    
    ChargerTransaction::where('transactionID', $transaction_id) 
      -> update(['status' => 'FINISHED']);
  }

  /**
   * Update charging info. what is the 
   * current kilowatt value.
   * 
   * @param string $transaction_id
   * @param int $value
   * @return void
   */
  public function update($transaction_id, $value)
  {
    $this -> logUpdate($transaction_id, $value);

    $charger_transaction = ChargerTransaction::where('transactionID', $transaction_id)
      -> first();

    $charger_transaction -> addKilowatt($value);
  }
  
  /**
   * log update transaction info.
   * 
   * @param string $transaction_id
   * @param int $value
   * @return void
   */
  private function logUpdate($transaction_id, $value)
  {
    Log::channel('transaction_update')->info(
      [
        'transaction_id' => $transaction_id, 
        'value' => $value,
        'additional_data' => request() -> all(),
        ]
      );
  }

  /**
   * log finish transaction info.
   * 
   * @param string $transaction_id
   * @return void
   */
  private function logFinish($transaction_id)
  {
    Log::channel('transaction_stop')->info([
      'transaction_id' => $transaction_id,
      'additional_data' => request() -> all(),
    ]);
  }
}

