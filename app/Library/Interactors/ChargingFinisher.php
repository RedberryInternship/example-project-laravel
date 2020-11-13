<?php

namespace App\Library\Interactors;

use App\Library\Entities\ChargingProcess\CacheOrderDetails;
use App\Library\Entities\ChargingFinish\MakeLastPayments;
use App\Library\Entities\ChargingFinish\OrderGetter;
use Illuminate\Support\Facades\Log;

class ChargingFinisher
{
  /**
   * Finish charging process.
   * 
   * @param int $transactionId
   * @return void
   */
  public static function finish( $transactionId )
  {
    $order = OrderGetter :: get( $transactionId );

    if(! $order) 
    {
      Log :: channel( 'feedback-finish' ) -> info( 'FINISHED - Transaction ID - ' . $transactionId );
      return;
    }
  
    $order -> updateFinishedTimestamp();
    
    MakeLastPayments  :: execute( $order );
    CacheOrderDetails :: execute( $order );
  }
}