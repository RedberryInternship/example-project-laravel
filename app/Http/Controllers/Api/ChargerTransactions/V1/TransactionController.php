<?php

namespace App\Http\Controllers\Api\ChargerTransactions\V1;

use App\Library\Interactors\OrdersMiddleware;
use App\Library\Interactors\ChargingFinisher;
use App\Library\Interactors\ChargingUpdater;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Order;

class TransactionController extends Controller
{
  # TODO Middleware for RealChargersBackEnd

  /**
   * Update charging info. what is the
   * current kilowatt value.
   *
   * @param  string  $transactionId
   * @param  int     $value
   * @return void
   */
   public function update( $transactionId, $value )
  {
    ChargingUpdater :: update( $transactionId, $value );
  }

  /**
   * Misha's route for letting us know when
   * the charging is completed and
   * the cable is disconnected.
   *
   * @param string $transactionId
   * @return void
   */
  public function finish( $transactionId )
  {
    ChargingFinisher :: finish( $transactionId );
  }
}
