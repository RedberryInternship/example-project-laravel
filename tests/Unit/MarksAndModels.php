<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Library\Entities\DataImports\ImportBeforeBoxwood\ImportMarks;
use App\Library\Entities\DataImports\ImportBeforeBoxwood\ImportCarModels;

class MarksAndModels extends TestCase 
{
  protected function setUp(): void
  {
    parent :: setUp();
    ImportMarks     :: execute();
    ImportCarModels :: execute();

    $this -> url = $this -> uri . 'get-models-and-marks';
  }

  /** @test */
  public function is_ok(): void 
  {
    $this -> get( $this -> url ) -> assertOk();
  }
}