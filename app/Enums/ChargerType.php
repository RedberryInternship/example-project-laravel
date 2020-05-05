<?php

namespace App\Enums;

class ChargerType extends Enum
{
  /**
   * Charger type which can have Combo 2
   * or CHAdeMO connector types.
   */
  const FAST = 'FAST';

  /**
   * Charger type which can have
   * Type 2 connector type.
   */
  const LVL2 = 'LVL2'; 
}