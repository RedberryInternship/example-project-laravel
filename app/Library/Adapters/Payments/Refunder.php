<?php

namespace App\Library\Adapters\Payments;

use Redberry\GeorgianCardGateway\Refund;

class Refunder
{
  public static function refund( $trxId, $RRN, $amount )
  {
    ( new Refund )
      -> setTrxId ( $trxId  )
      -> setRRN   ( $RRN    )
      -> setAmount( $amount )
      -> execute();
  }
}