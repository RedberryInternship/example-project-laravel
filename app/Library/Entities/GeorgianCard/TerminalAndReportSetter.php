<?php

namespace App\Library\Entities\GeorgianCard;

use Redberry\GeorgianCardGateway\Responses\PaymentAvail;

//todo Vobi,  დეტალურად ავღწეროთ თუ რისთვის დატომ გამოიყენებ ეს კლასი.
class TerminalAndReportSetter
{
  /**
   * Set pos terminal and report.
   *
   * @param  PaymentAvail $data
   * @return void
   */
  public static function set( PaymentAvail &$data ): void
  {
    $accountId     = request() -> get( 'o_account_id' );
    $chargerReport = request() -> get( 'o_charger_report' ) ?? 'No report';
    $data -> setPurchaseLongDesc( $chargerReport );
    $accountId && $data -> setAccountId( $accountId );
  }

}
