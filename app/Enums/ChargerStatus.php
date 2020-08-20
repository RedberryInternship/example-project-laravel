<?php

namespace App\Enums;

class ChargerStatus extends Enum
{
  /**
   * Charger that is online but 
   * is not charging right now.
   */
  const ACTIVE      = 'ACTIVE';

  /**
   * Charger that is offline. and can't charge car.
   */
  const INACTIVE    = 'INACTIVE'; 

  /**
   * Charger that is online but also
   * is charging at the moment.
   */
  const CHARGING    = 'CHARGING';

  /**
   * Charger that is in our local db but not present in 
   * real chargers service.
   */
  const NOT_PRESENT = 'NOT_PRESENT';
}