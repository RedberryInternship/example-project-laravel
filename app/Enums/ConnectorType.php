<?php

namespace App\Enums;

class ConnectorType extends Enum
{
  /**
   * Type 2 connector type.
   * Lvl2 charger.
   */
  const TYPE_2  = 'Type 2';

  /**
   * Combo 2 connector type.
   * Fast charger.
   */
  const COMBO_2 = 'Combo 2';

  /**
   * CHAdeMO connector type.
   * Fast charger.
   */
  const CHADEMO = 'CHAdeMO';
}