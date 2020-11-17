<?php

namespace App\Library\Interactors;

use App\Library\Entities\ChargingProcess\CacheOrderDetails;
use App\Library\Entities\ChargingFinish\MakeLastPayments;
use App\Library\Entities\ChargingFinish\FinishAndNotify;
use App\Library\Entities\ChargingFinish\OrderGetter;
use App\Library\Entities\Log;

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
      return;
    }
  
    $order -> updateFinishedTimestamp();
    
    MakeLastPayments  :: execute( $order );
    CacheOrderDetails :: execute( $order );
    FinishAndNotify   :: execute( $order );

    Log :: orderSuccessfullyFinished( $transactionId );
  }
}