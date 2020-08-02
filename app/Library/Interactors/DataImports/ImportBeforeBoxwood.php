<?php

namespace App\Library\Interactors\DataImports;

use App\Library\Entities\DataImports\ImportBeforeBoxwood\ImportConnectorTypes;
use App\Library\Entities\DataImports\ImportBeforeBoxwood\ImportCarModels;
use App\Library\Entities\DataImports\ImportBeforeBoxwood\ImportRoles;
use App\Library\Entities\DataImports\ImportBeforeBoxwood\ImportMarks;

class ImportBeforeBoxwood
{
  /**
   * Import data before boxwood data is imported.
   * 
   * @return void
   */
  public static function execute(): void
  {
    ImportConnectorTypes :: execute();
    ImportRoles          :: execute();
    ImportMarks          :: execute();
    ImportCarModels      :: execute();
  }
}