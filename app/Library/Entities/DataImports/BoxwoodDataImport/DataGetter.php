<?php

namespace App\Library\Entities\DataImports\BoxwoodDataImport;

class DataGetter
{
  /**
   * Get json data.
   * 
   * @param  string $name
   * @return array
   */
  public static function get( string $name )
  {
    $pathToData = base_path('database/boxwood-data/') . $name . '.json';
    $data       = file_get_contents( $pathToData );
    $data       = json_decode( $data );

    return $data -> RECORDS;
  }
}