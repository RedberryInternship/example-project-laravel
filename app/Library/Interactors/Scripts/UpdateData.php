<?php

namespace App\Library\Interactors\Scripts;

use App\Library\Entities\Scripts\OldOrderDataUpdate;
use App\Library\Entities\Scripts\CacheForeignKeys;

class UpdateData {
  /**
   * Update old order data.
   * 
   * @return void
   */
  public static function oldOrders(): void
  {
    OldOrderDataUpdate :: execute();
  }

  /**
   * Cache orders and payments far 
   * relations foreign keys for easy access.
   * 
   * @return void
   */
  public static function cacheOrdersAndPaymentsForeignKeys(): void
  {
    CacheForeignKeys :: execute();
  }
}