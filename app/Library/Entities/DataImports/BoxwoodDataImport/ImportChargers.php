<?php

namespace App\Library\Entities\DataImports\BoxwoodDataImport;

use App\Charger;
use App\ChargerTag;

class ImportChargers
{
  /**
   * Import chargers.
   * 
   * @return void
   */
  public static function execute(): void
  {
    $chargers           = DataGetter :: get( 'chargers' );
    $formattedChargers  = self :: format( $chargers );

    Charger :: insert( $formattedChargers );
    self    :: connectTags( $chargers );
  }

  /** 
   * Format chargers.
   * 
   * @param  array $chargers
   * @return array
   */
  public static function format( $chargers )
  {
    return array_map( function( $charger ) {

      return [
        'old_id'          => $charger -> id,
        'name'            => self :: makeName( $charger -> code ),
        'charger_id'      => $charger -> charger_id,
        'code'            => $charger -> code,
        'location'        => self :: makeLocation( $charger ),                    
        'public'          => $charger -> paid,                    
        'lat'             => $charger -> latitude,                  
        'lng'             => $charger -> longitude,
        'iban'            => $charger -> iban,
        'active'          => $charger -> status == 0,
      ];
    }, $chargers );
  }

  /**
   * Make charger name.
   * 
   * @param string $code
   * 
   * @return string
   */
  private static function makeName( $code ): string
  {
    return json_encode(
      [
        'en' => 'Charger '             . $code,
        'ru' => 'Зарядное Устройство ' . $code,
        'ka' => 'დამტენი '             . $code,
      ]
    );
  }

  /**
   * Make charger location.
   * 
   * @param  object $charger
   * @return string
   */
  private static function makeLocation( $charger ): string
  {
    return json_encode(
      [
        'en'    => urldecode( $charger -> description_en ),
        'ru'    => urldecode( $charger -> description_ru ),
        'ka'    => urldecode( $charger -> description    ),
      ]
    );
  }

  /**
   * Connect charger to tags.
   * 
   * @param  $chargers
   * @return void
   */
  private static function connectTags( $chargers )
  {
    foreach( Charger :: all() as $charger )
    {
      $DBChargers[ $charger -> old_id ] = $charger -> id;
    }

    $filteredChargers = array_filter( $chargers, function( $charger ) {
      return !! $charger -> category_id;
    });

    $chargerTags = array_map( function( $charger ) use( $DBChargers ) {
      return [
        'charger_id' => $DBChargers[ $charger -> id ],
        'tag_id'     => $charger -> category_id,
      ];
    }, $filteredChargers );

    ChargerTag :: insert( $chargerTags );
  }
}