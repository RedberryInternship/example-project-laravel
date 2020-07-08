<?php

namespace App\Library\Sync;

use App\Facades\Charger as MishasCharger;

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
    $mishasChargers = $this -> getAllChargers();
    $this -> insertOrUpdateChargers($mishasChargers);
  }

  /**
   * Insert or update one.
   * 
   * @param int $charger_id
   * 
   * @return void
   */
  public function insertOrUpdateOne($charger_id)
  {
    $m_charger = $this -> getCharger($charger_id);

    $connectors = $m_charger -> connectors;
    $only_charger_data = $this -> parseCharger($m_charger);

    $this -> insertOrUpdateSingleCharger($only_charger_data, $connectors );
  }


  /**
   * Get All the chargers from Misha's Database.
   * 
   * @return array<object>
   */
  private function getAllChargers()
  {   
    return MishasCharger::all();
  }

  /**
   * Get specific charger from Misha's Database.
   * 
   * @param int $id
   * @return object
   */
   private function getCharger($id){
      return MishasCharger::find($id);; 
   }

}