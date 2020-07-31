<?php

namespace App\Library\Interactors;

use Redberry\GeorgianCardGateway\Contracts\GeorgianCardHandler;

use App\Library\Entities\GeorgianCard\TerminalAndReportSetter;
use App\Library\Entities\GeorgianCard\PrimaryTRXSetter;
use App\Library\Entities\GeorgianCard\SaveCardRefunder;
use App\Library\Entities\GeorgianCard\UserCardSaver;
use App\Library\Entities\GeorgianCard\Payer;

use Redberry\GeorgianCardGateway\Responses\RegisterPayment;
use Redberry\GeorgianCardGateway\Responses\PaymentAvail;

class GeorgianCard implements GeorgianCardHandler
{
  /**
   * Check if payment is available, set primaryTrxId if needed
   * and do all the necessary operations.
   * 
   * @param  PaymentAvail
   * @return PaymentAvaL
   */
  public function paymentAvail(PaymentAvail $data): PaymentAvail
  {
    if( UserCardSaver :: shouldSaveUserCard() )
    {
      UserCardSaver :: save();
    }
    else
    {
      TerminalAndReportSetter :: set( $data );
      PrimaryTRXSetter        :: set( $data );
      Payer                   :: pay();
    }

    return $data;
  }
  
  /**
   * Confirm the payment.
   * 
   * @param  RegisterPayment $data
   * @return RegisterPayment
   */
  public function registerPayment(RegisterPayment $data): RegisterPayment
  {
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