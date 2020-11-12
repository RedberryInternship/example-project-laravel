<?php

namespace App\Library\Entities\Nova\ChargerTerminals;

use Illuminate\Support\Facades\DB;

//todo Vobi,  დეტალურად ავღწეროთ თუ რისთვის დატომ გამოიყენებ ეს კლასი.
class TerminalsGetter
{
  /**
   * Get all pos terminals from db.
   *
   * @return JSON
   */
  public static function get()
  {
    $sqlQuery = 'select id, title from terminals';

    return DB :: select( $sqlQuery );
  }
}
