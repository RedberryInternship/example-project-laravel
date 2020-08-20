<?php

namespace App\Library\Entities\DataImports\BoxwoodDataImport;

use App\PhoneCode;

class ImportPhoneCodes
{
  /**
   * Import phone codes.
   * 
   * @return void
   */
  public static function execute(): void
  {
    $phoneCodes           = DataGetter :: get( 'phone_codes' ) -> RECORDS;
    $formattedPhoneCodes  = self :: format( $phoneCodes );

    PhoneCode :: insert( $formattedPhoneCodes );
  }

  /**
   * Format phone codes.
   * 
   * @param  array $phoneCodes
   * @return array
   */
  public static function format( $phoneCodes ): array
  {
    $formattedPhoneCodes = [];
    foreach( $phoneCodes as $key => $value )
    {
      $formattedPhoneCodes []= [ 'country_code' => $key, 'phone_code' => $value ];
    }

    return $formattedPhoneCodes;
  }
}