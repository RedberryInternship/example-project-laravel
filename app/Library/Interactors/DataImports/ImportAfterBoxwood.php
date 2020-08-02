<?php

namespace App\Library\Interactors\DataImports;

use App\Library\Entities\DataImports\ImportAfterBoxwood\ImportChargingPrices;

class ImportAfterBoxwood
{
  /**
   * Import data before boxwood data is imported.
   * 
   * @return void
   */
  public static function execute(): void
  {
    ImportChargingPrices :: execute();
  }
}