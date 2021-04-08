<?php

namespace App\Library\Entities\Business\Groups;

use App\Group;
use App\User;

class StoreAllCompanyChargersIntoGroup
{
  /**
   * Store all the company chargers into the group.
   * 
   * @param int $groupId
   * @return void
   */
  public static function execute( $groupId )
  {
    $userId = auth() -> user() -> id;
    $user   = User :: with( 'company.chargers' ) -> findOrFail( $userId );
    $group  = Group :: with( 'chargers' ) -> findOrFail( $groupId );
    
    $companyChargerIds = $user 
      -> company 
      -> chargers 
      -> map(function($charger) {
        return $charger -> id;
      })
      -> toArray();

    $groupChargerIds = $group 
      -> chargers 
      -> map(function($charger) {
        return $charger -> id;
      }) 
      -> toArray();

    $assignableChargerIds = array_diff( $companyChargerIds, $groupChargerIds );

    $group -> chargers() -> attach( $assignableChargerIds );
  }
}