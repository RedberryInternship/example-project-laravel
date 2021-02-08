<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Library\Entities\DataImports\ImportBeforeBoxwood\ImportMarks;
use App\Library\Entities\DataImports\ImportBeforeBoxwood\ImportCarModels;

class MarksAndModels extends TestCase {
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
  
  /** @test */
  public function marks_and_models_structure(): void 
  {
    $this -> get( $this -> url ) -> assertJsonStructure(
      [
        'data' => [
          '*' => [
            'id',
            'name',
            'models' => [
              '*' => [
                'id',
                'mark_id',
                'name',
              ],
            ],
          ]
        ]
      ]
    );
  }
}