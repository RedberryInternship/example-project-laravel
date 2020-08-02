<?php

namespace App\Library\Interactors\DataImports;

use App\Library\Entities\DataImports\BoxwoodDataImport\ImportChargerConnectorTypes;
use App\Library\Entities\DataImports\BoxwoodDataImport\ImportPhoneCodes;
use App\Library\Entities\DataImports\BoxwoodDataImport\ImportUserCards;
use App\Library\Entities\DataImports\BoxwoodDataImport\ImportChargers;
use App\Library\Entities\DataImports\BoxwoodDataImport\ImportPayments;
use App\Library\Entities\DataImports\BoxwoodDataImport\ImportOrders;
use App\Library\Entities\DataImports\BoxwoodDataImport\ImportUsers;
use App\Library\Entities\DataImports\BoxwoodDataImport\ImportTags;

class BoxwoodDataImporter
{
  /**
   * Import boxwood data.
   * 
   * @return void
   */
  public static function import(): void
  {
    ImportUsers      :: execute();
    ImportUserCards  :: execute();
    ImportTags       :: execute();
    ImportChargers   :: execute();
    ImportChargerConnectorTypes :: execute();
    ImportOrders     :: execute();
    ImportPayments   :: execute();
    ImportPhoneCodes :: execute();
  }
}