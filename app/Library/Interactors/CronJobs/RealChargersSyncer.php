<?php

namespace App\Library\Interactors\CronJobs;

use App\Library\Entities\CronJobs\RealChargersSync\CleanUpAdditionalChargers;
use App\Library\Entities\CronJobs\RealChargersSync\ChargersGetter;
use App\Library\Entities\CronJobs\RealChargersSync\ChargersParser;
use App\Library\Entities\CronJobs\RealChargersSync\ChargersEditor;

use App\Library\DataStructures\RealChargersSync as Data;

/**
 * Cron Job class for retrieving real chargers from
 * chargers back-end and synchronizing it with
 * our database.
 */
class RealChargersSyncer
{
  /**
   * Sync all chargers.
   *
   * @return void
   */
  public static function syncAll()
  {
    $realChargers   = ChargersGetter :: getAll();
    $parsedChargers = ChargersParser :: parseAll( $realChargers );
    $data           = Data :: build() -> setRealChargers( $parsedChargers );

    ChargersEditor :: update( $data );

    CleanUpAdditionalChargers :: execute( $realChargers );
  }

  /**
   * Sync single charger.
   *
   * @param  int $id
   * @return void
   */
  public static function syncOne( $id )
  {
    $realCharger   = ChargersGetter :: getOne( $id );
    $parsedCharger = ChargersParser :: parseOne( $realCharger );
    $data          = Data :: build() -> setRealChargers([ $parsedCharger ]);

    ChargersEditor :: update( $data );
  }
}
