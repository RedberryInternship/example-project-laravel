<?php

namespace App\Library\Interactors;

use Redberry\GeorgianCardGateway\Contracts\GeorgianCardHandler;

use App\Library\Entities\GeorgianCard\TerminalAndReportSetter;
use App\Library\Entities\GeorgianCard\PaymentStatusChecker;
use App\Library\Entities\GeorgianCard\PrimaryTRXSetter;
use App\Library\Entities\GeorgianCard\SaveCardRefunder;
use App\Library\Entities\GeorgianCard\FailureHandler;
use App\Library\Entities\GeorgianCard\UserCardSaver;
use App\Library\Entities\GeorgianCard\Payer;
use Illuminate\Support\Facades\Log;
use App\Order;

use Redberry\GeorgianCardGateway\Responses\RegisterPayment;
use Redberry\GeorgianCardGateway\Responses\PaymentAvail;

class GeorgianCard implements GeorgianCardHandler
{
  /**
   * Check if payment is available, 
   * set primaryTrxId if needed.
   * 
   * @param  PaymentAvail
   * @return PaymentAvaL
   */
  public function paymentAvail(PaymentAvail $data): PaymentAvail
  { 
    Log :: channel( 'payment-responses' ) -> info([ 'PaymentAvail', request() -> all() ]);

    TerminalAndReportSetter :: set( $data );
    PrimaryTRXSetter        :: set( $data );
    
    return $data;
  }
  
  /**
   * Confirm the payment and do necessary operations.
   * 
   * @param  RegisterPayment $data
   * @return RegisterPayment
   */
  public function registerPayment(RegisterPayment $data): RegisterPayment
  {
    Log :: channel( 'payment-responses' ) -> info([ 'RegisterPayment', request() -> all() ]);

    if( PaymentStatusChecker :: succeeded() )
    {
      UserCardSaver :: shouldSaveUserCard() 
        ? UserCardSaver :: save()
        : Payer :: createPaymentRecord();
    }
    else
    {
      FailureHandler :: handle();
    }
    
    if(! UserCardSaver :: shouldSaveUserCard())
    {
      Order :: find(request()->get('o_id')) -> unlockPayments();
    }

    return $data;
  }

  /**
   * Success method will be executed if
   * transaction is to end successfully.
   * 
   * @return mixed
   */
  public function success()
  {
    SaveCardRefunder :: RefundIfCardSaved();
    dump( 'Success' );
  }

  /**
   * Failed method will be executed if
   * transaction is to end with failure.
   * 
   * @return mixed
   */
  public function failure()
  {
    dump( 'Failure' );
  }
}