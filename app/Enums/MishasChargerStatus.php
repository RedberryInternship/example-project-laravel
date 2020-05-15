<?php

namespace App\Enums;

class MishasChargerStatus extends Enum
{
  /**
   * Charger is free.
   */
  const FREE     = 0;

  /**
   * Charger is currently charging.
   */
  const CHARGING = 1;

  /**
   * All other cases.
   */
  const OTHER    = -1;
}