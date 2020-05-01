<?php

namespace App\Enums;

class PaymentType extends Enum
{
  /**
   * Checking payment in order to be sure
   * that the user card is valid.
   */
  const CHECK   = 'CHECK';

  /**
   * Payment status for fine.
   */
  const FINE    = 'FINE';

  /**
   * Payment status for usual payment cuts.
   */
  const CUT     = 'CUT';

  /**
   * Payment status for payments which serve to
   * refund the users when transaction is finished and
   * they payed more then they were charged.
   */
  const REFUND  = 'REFUND';
}