<?php

namespace App\Library\Entities\DataImports\BoxwoodDataImport;

use App\Tag;

class ImportTags
{
  /**
   * Import tags.
   * 
   * @return void
   */
  public static function execute(): void
  {
    $tags = DataGetter :: get( 'categories' );
    $tags = array_map( function( $tag ) {      
      $name = json_encode(
        [
        'en' => $tag -> name_en,
        'ru' => $tag -> name_ru,
        'ka' => $tag -> name,
        ]
      );

      return [
        'name' => $name,
        'old_id' => $tag -> id,
      ];
    }, $tags );
  
    Tag :: insert( $tags );
  }
}