<?php

namespace App\Library\Entities\CronJobs\RealChargersSync;

use App\Facades\Charger as RealCharger;

class ChargersGetter
{
  /**
   * Get all the real chargers info.
   * 
   * @return array<object>
   */
  public static function getAll()
  {
    return RealCharger :: all();
  }

  /**
   * Get single charger's info.
   * 
   * @param  int $id
   * @return object
   */
  public static function getOne( $id )
  {
    return RealCharger :: find( $id );
  }
}