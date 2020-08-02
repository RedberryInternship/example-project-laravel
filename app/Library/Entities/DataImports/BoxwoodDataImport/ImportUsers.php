<?php

namespace App\Library\Entities\DataImports\BoxwoodDataImport;

use App\User;

class ImportUsers
{
  /**
   * Import users data.
   * 
   * @return void
   */
  public static function execute(): void
  {
    $users = DataGetter :: get( 'users' );
    $users = self :: format( $users );
    
    User :: insert( $users );
  }

  /**
   * Format into insertable array.
   * 
   * @param  array
   * @return array
   */
  public static function format( $users )
  {
    return array_map( function( $user ) {
      return [
        'old_id'       => intval( $user -> id ),
        'first_name'   => $user -> first_name,
        'last_name'    => $user -> last_name,
        'email'        => $user -> email,
        'password'     => $user -> password,
        'phone_number' => $user -> phone_number, # what are we gonna do about phone numbers?
        'active'       => 1, # Should this be like this?
        'verified'     => 0, # Should this be like this?
        'role_id'      => 1,
      ];
    }, $users );
  }
}