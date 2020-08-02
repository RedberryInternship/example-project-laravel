<?php

namespace App\Library\Entities\DataImports\BoxwoodDataImport;

use App\User;
use App\UserCard;

class ImportUserCards
{
  /**
   * Import user cards.
   * 
   * @return void
   */
  public static function execute(): void
  {
    $userCards      = DataGetter :: get( 'credit_cards' );
    $filteredUsers  = self :: filteredUsers();
    $userCards      = self :: format( $userCards, $filteredUsers );

    UserCard :: insert( $userCards );
  }

  /**
   * Format into insertable array.
   * 
   * @param  array $userCards
   * @param        $users
   * @return array
   */
  private static function format( $userCards, $filteredUsers )
  {
    return array_map( function( $userCard ) use( $filteredUsers ) {      
      return [
        'old_id'         => $userCard -> id,
        'masked_pan'     => $userCard -> masked_pan,
        'transaction_id' => $userCard -> trx_id,
        'card_holder'    => $userCard -> card_holder,
        'user_id'        => $filteredUsers[ $userCard -> user_id ],
        'user_old_id'    => $userCard -> user_id,
        'active'         => false,
      ];
    }, $userCards );
  }

  /**
   * Filtered users.
   * 
   * @return array
   */
  private static function filteredUsers()
  { 
    $filteredUsers = [];

    foreach( User :: all() as $user )
    {
      $filteredUsers[ $user -> old_id ] = $user -> id;
    }

    return $filteredUsers;
  }
}