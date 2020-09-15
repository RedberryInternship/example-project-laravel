<?php

namespace App\Http\Controllers\Api\ChargerTransactions\V1;

use App\Library\Interactors\OrdersMiddleware;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Order;

class TransactionController extends Controller
{
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
    OrdersMiddleware :: check( $transaction_id );

    $order = Order :: with( 'kilowatt' ) 
      -> where( 'charger_transaction_id', $transaction_id ) 
      -> first();

    if( $order )
    {
      Log :: channel( 'feedback-update' ) -> info( 'Update Happened | Transaction ID - ' . $transaction_id . ' | Value - ' . $value );
      $order -> kilowatt -> updateConsumedKilowatts( $value );
      $order -> chargingUpdate();
    }
    else
    {
      Log :: channel( 'feedback-update' ) -> info( 'Nothing To Update |'. $transaction_id );
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
    $order = Order :: where( 'charger_transaction_id', $transaction_id ) -> first();

    if( $order )
    {
      $order -> finish();
      
      Log :: channel( 'feedback-finish' ) -> info( 'FINISHED - Transaction ID - ' . $transaction_id );
    }
    else
    {
      Log :: channel( 'feedback-finish' ) -> info( 'Nothing To Finish - Transaction ID - ' . $transaction_id );
    }
  }
}

