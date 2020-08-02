<?php

namespace App\Library\Entities\DataImports\ImportBeforeBoxwood;

use Illuminate\Support\Facades\DB;

use App\Enums\Role as RoleEnum;

class ImportRoles
{
  /**
   * Insert roles.
   * 
   * @return void
   */
  public static function execute(): void
  {
    DB :: table( 'roles' ) -> insert(
      [
        [   
            'name' => RoleEnum :: REGULAR,
        ],
        [
            'name' => RoleEnum :: ADMIN,
        ],
        [
            'name' => RoleEnum :: BUSINESS,
        ],
        [
            'name' => RoleEnum :: PAYMENT,
        ]
      ]
    );
  }
}