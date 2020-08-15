<?php

namespace App\Library\Entities\DataImports\BoxwoodDataImport;

class DataGetter
{
  /**
   * Get json data.
   * 
   * @param  string $name
   * @return array|object
   */
  public static function get( string $name )
  {
    $pathToData = base_path('database/boxwood-data/v2/') . $name . '.json';
    $data       = file_get_contents( $pathToData );
    
    return json_decode( $data );
  }
}