<?php

namespace App\Library\Entities\ChargingStart;

use App\Order;

class KilowattRecordCreator
{
  /**
   * Create first kilowatt record.
   * 
   * @param   Order $order
   * @return  void
   */
  public static function create( Order $order ): void
  {
    $order -> kilowatt() -> create([ 'consumed' => 0 ]);
  }
}