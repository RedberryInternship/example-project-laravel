<?php


namespace App\Http\Controllers\Api\ChargerTransactions\V1;

use App\Http\Controllers\Controller;
use App\Order;

class TransactionController extends Controller
{
  /**
   * Order instance.
   */
  private $order;

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
    $this -> order = Order :: with( 'kilowatt' ) 
      -> where( 'charger_transaction_id', $transaction_id ) 
      -> first();

    $this -> order -> kilowatt -> updateConsumedKilowatts( $value );
    $this -> order -> chargingUpdate();
  }

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
    $this -> order = Order ::  where( 'charger_transaction_id', $transaction_id ) -> first();

    $this -> order -> finish();
  }
}

