<?php


namespace App\Http\Controllers\Api\ChargerTransactions\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

use App\Enums\OrderStatus;

use App\Order;

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
  public function finish( $transaction_id )
  {
    $this -> logFinish( $transaction_id );    
    
    Order :: where( 'charger_transaction_id', $transaction_id ) 
                      -> update([ 'charging_status' => OrderStatus :: FINISHED ]);
  }

  /**
   * Update charging info. what is the 
   * current kilowatt value.
   * 
   * @param string $transaction_id
   * @param int $value
   * @return void
   */
  public function update( $transaction_id, $value )
  {
    $this -> logUpdate( $transaction_id, $value );

    $order = Order :: where( 'charger_transaction_id', $transaction_id ) -> first();

    $order -> addKilowatt( $value );
  }
  
  /**
   * log update transaction info.
   * 
   * @param string $transaction_id
   * @param int $value
   * @return void
   */
  private function logUpdate( $transaction_id, $value )
  {
    Log::channel( 'transaction_update' )->info(
        [
          'transaction_id'  => $transaction_id, 
          'value'           => $value,
        ]
      );
  }

  /**
   * log finish transaction info.
   * 
   * @param string $transaction_id
   * @return void
   */
  private function logFinish( $transaction_id )
  {
    Log::channel( 'transaction_stop' )->info(
      [
      'transaction_id'  => $transaction_id,
      ]
    );
  }
}

