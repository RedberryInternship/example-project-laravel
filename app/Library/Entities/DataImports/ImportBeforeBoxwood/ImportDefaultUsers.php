<?php

namespace App\Library\Entities\DataImports\ImportBeforeBoxwood;

use Illuminate\Support\Facades\DB;

class ImportDefaultUsers
{
  /**
   * Import default, necessary users.
   * 
   * @return void
   */
  public static function execute(): void
  {
    DB::table('users')->insert(
      [
        self :: espace(),
        self :: payment(),
      ]
    );
  }

  /**
   * Espace user.
   */
  private static function espace(): array
  {
    return [   
      'role_id'            => 2,
      'first_name'         => 'Admin',
      'last_name'          => 'Espace',
      'phone_number'       => '111',
      'email'              => 'admin@espace.ge',
      'active'             =>  true,
      'verified'           =>  true,
      'password'           =>  bcrypt('admin2000'),
    ];
  }

  /**
   * Payment user.
   */
  private static function payment(): array
  {
    return [
      'role_id'            => 4,
      'first_name'         => 'Payment',
      'last_name'          => 'Espace',
      'phone_number'       => '222',
      'email'              => 'payment@espace.ge',
      'active'             =>  true,
      'verified'           =>  true,
      'password'           =>  bcrypt('M9QwdZh1i4MHYV5v'),
    ];
  }
}