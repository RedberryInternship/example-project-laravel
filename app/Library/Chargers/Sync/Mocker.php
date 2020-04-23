<?php

namespace App\Library\Chargers\Sync;

use App\Library\Testing\MishasMockCharger;


class Mocker extends Base{

   /**
   * insert or update with custom Mock Misha's chargers
   * 
   * @param array<App\Library\Testing\MishasMockCharger> $mockChargers
   */
  public function insertOrUpdate($mockChargers = null)
  {

    if(!$mockChargers)
    {
      $n = random_int(10, 20);
      $mockChargers = $this -> generateMockChargers($n);
    }

    $this -> insertOrUpdateChargers($mockChargers);
  }

  /**
   * Mock insert or update one
   * 
   * @param object $m_charger
   */
  public function insertOrUpdateOne($m_charger)
  {
    $connectors = $m_charger -> connectors;
    $only_charger_data = $this -> parseCharger($m_charger);

    $this -> insertOrUpdateSingleCharger($only_charger_data, $connectors);
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