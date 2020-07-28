<?php

namespace App\Library\Adapters\Payments;

use Redberry\GeorgianCardGateway\Refund;

class Refunder
{
  public static function refund( $trxId, $RRN, $amount )
  {
    Refund :: build()
      -> setTrxId ( $trxId  )
      -> setRRN   ( $RRN    )
      -> setAmount( $amount )
      -> execute();
  }
}