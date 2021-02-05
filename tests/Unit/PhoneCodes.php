<?php

namespace Test\Unit;

use Tests\TestCase;
use App\Library\Entities\DataImports\BoxwoodDataImport\ImportPhoneCodes;

class PhoneCodes extends TestCase {
  protected function setUp(): void
  {
    parent :: setUp();
    $this -> url = $this -> uri . 'phone-codes';
    ImportPhoneCodes :: execute();
  }

  /** @test */
  public function is_ok(): void 
  {
    $this -> get( $this -> url ) -> assertOk();
  }
}