<?php

namespace App\Library\Interactors\Business;

use App\Library\Entities\Business\Groups\GroupChargersChargingPricesDestroyer;
use App\Library\Entities\Business\Groups\StoreAllCompanyChargersIntoGroup;

class Groups
{
  /**
   * Delete charging prices of all the group chargers.
   * 
   * @param int $groupId
   * @return void
   */
  public static function deleteGroupChargersChargingPrices( $groupId )
  {
    GroupChargersChargingPricesDestroyer :: destroy( $groupId );
  }

  /**
   * Store all the company chargers into the group.
   * 
   * @param int $groupId
   * @return void
   */
  public static function storeAllCompanyChargersIntoGroup( $groupId )
  {
    StoreAllCompanyChargersIntoGroup :: execute( $groupId );
  }
}