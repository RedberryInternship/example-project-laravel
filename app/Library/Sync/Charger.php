<?php

namespace App\Library\Sync;

use App\Library\Entities\CronJobs\RealChargersSync\ChargersGetter;
use App\Library\Entities\CronJobs\RealChargersSync\ChargersParser;

class Charger extends Base
{

   /**
   * Insert or update existing charger records in 
   * database with Misha's Chargers
   * 
   * @return void
   */
  public function insertOrUpdate()
  {
    $mishasChargers = ChargersGetter :: getAll();
    $this -> insertOrUpdateChargers($mishasChargers);
  }

  /**
   * Insert or update one.
   * 
   * @param int $chargerId
   * 
   * @return void
   */
  public function insertOrUpdateOne($chargerId)
  {
    $realCharger   = ChargersGetter :: getOne( $chargerId );
    $parsedCharger = ChargersParser :: parseOne( $realCharger);

    $this -> insertOrUpdateSingleCharger( $parsedCharger );
  }

}