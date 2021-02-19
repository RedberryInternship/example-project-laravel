<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Library\Entities\Helper as Help;

class Helper extends TestCase
{
  protected function setUp(): void
  {
    parent :: setUp();
    $this -> minutes1 = 60;
    $this -> minutes2 = 69;
    $this -> minutes3 = 130;

    $this -> time1 = '01:00';
    $this -> time2 = '01:09';
    $this -> time3 = '02:10';
  }

  /** @test */
  public function converts_number_to_gel(): void
  {
    $time1 = Help::convertMinutesToHHMM($this->minutes1);
    $time2 = Help::convertMinutesToHHMM($this->minutes2);
    $time3 = Help::convertMinutesToHHMM($this->minutes3);

    $this -> assertEquals( $this -> time1, $time1 );
    $this -> assertEquals( $this -> time2, $time2 );
    $this -> assertEquals( $this -> time3, $time3 );
  }
}