<?php


namespace App\Http\Controllers\Api\ChargerTransactions\V1;

use App\Library\Interactors\NotConfirmedOrdersChecker;
use App\Library\Interactors\Firebase;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
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
   * @param  string  $transaction_id
   * @param  int     $value
   * @return void
   */
   public function update( $transaction_id, $value )
  {
    NotConfirmedOrdersChecker :: check( $transaction_id );

    $this -> order = Order :: with( 'kilowatt' ) 
      -> where( 'charger_transaction_id', $transaction_id ) 
      -> first();

    if( $this -> order )
    {
      $this -> order -> kilowatt -> updateConsumedKilowatts( $value );
      $this -> order -> chargingUpdate();
    }
    else
    {
      Log :: channel( 'transaction_update' ) -> info(
        'There is no such order with transaction id of '. $transaction_id,
      );
    }
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

    if( $this -> order )
    {
      $this -> order -> finish();
      Firebase :: sendFinishNotificationWithData( $transaction_id );
    }
    else
    {
      Log :: channel( 'transaction_stop' ) -> info(
        'There is no such order with transaction id of '. $transaction_id,
      );
    }

  }
}

