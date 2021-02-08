<?php

namespace App\Library\Testing;

use Faker\Generator as Faker;

class ChargerMocker {

  public function __construct(Faker $faker)
  {
    $this -> faker = $faker;
  }

  /**
   * Generate mock chargers
   * 
   * @param int $numberOfInstances
   * @return array<App\Library\Testing\MishasMockCharger> 
   */
  public function generateMockChargers($numberOfInstances)
  {
    $mockChargers = [];
    while($numberOfInstances--){
      $mockChargers []= $this -> generateSingleMockCharger();
    }

    return $mockChargers;
  }

  /**
   * Generate new mock charger
   * 
   * @return App\Library\Testing\MishasMockCharger
   */
  public function generateSingleMockCharger() 
  {
    return new MishasMockCharger($this -> faker);
  }
}