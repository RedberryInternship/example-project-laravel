<?php

namespace App\Library\Interactors\Business;

use App\Library\Entities\Business\Orders\OrderInfoGetter;

class Orders
{
  /**
   * Get specific order data.
   * 
   * @param int $id
   * @return array
   */
  public static function getInfo( $id )
  {
    return OrderInfoGetter :: get( $id );
  }
}