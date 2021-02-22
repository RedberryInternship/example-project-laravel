<?php

namespace App\Library\Entities\GeorgianCard;

use Redberry\GeorgianCardGateway\Responses\PaymentAvail;

/**
 * We need to set terminal and additional report 
 * information(that is saved on charger_connector_type)
 * and send it to Georgian Card in order for Espace
 * to identify which transaction came from which charger(in Bank Records).
 */
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
