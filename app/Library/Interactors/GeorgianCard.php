<?php

namespace App\Library\Interactors;

use Redberry\GeorgianCardGateway\Contracts\GeorgianCardHandler;

use App\Library\Entities\GeorgianCard\PrimaryTRXGetter;
use App\Library\Entities\GeorgianCard\SaveCardRefunder;
use App\Library\Entities\GeorgianCard\UserCardSaver;
use App\Library\Entities\GeorgianCard\Payer;

use Redberry\GeorgianCardGateway\Responses\RegisterPayment;
use Redberry\GeorgianCardGateway\Responses\PaymentAvail;

class GeorgianCard implements GeorgianCardHandler
{
  public function paymentAvail(PaymentAvail $data): PaymentAvail
  {
    if( UserCardSaver :: shouldSaveUserCard() )
    {
      UserCardSaver :: save();
    }
    else
    {
      Payer :: pay();
    }

    $primaryTRX    = PrimaryTRXGetter :: get();
    $data -> setPrimaryTrxPcid( $primaryTRX );

    $accountId     = request() -> get( 'o_account_id' );
    $chargerReport = request() -> get( 'o_charger_report' ) ?? 'No report';
    $data -> setPurchaseLongDesc( $chargerReport );
    $accountId && $data -> setAccountId( $accountId );

    return $data;
  }
  
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