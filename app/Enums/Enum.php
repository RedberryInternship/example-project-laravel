<?php

namespace App\Enums;

use ReflectionClass;

class Enum
{
  /**
   * Get constants key value pair array.
   * 
   * @return array
   */
  public static function getConstants() : array
  {
    $reflector = new ReflectionClass( new static );
    return $reflector -> getConstants();
  }

  /**
   * Get constants values.
   * 
   * @return array
   */
  public static function getConstantsValues() : array
  {
    $constantsArr = static :: getConstants();

    return array_values( $constantsArr );
  }

  /**
   * Get constants names.
   * 
   * @return array
   */
  public static function getConstantsNames() : array
  {
    $constantsArr = static :: getConstants();

    return array_keys( $constantsArr );
  }
}