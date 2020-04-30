<?php

namespace App\Enums;

class ChargingType extends Enum
{
  /**
   * Charging type which requires user to specify the
   * amount of money with which car should be charged.
   */
  const BY_AMOUNT   = 'BY_AMOUNT';

  /**
   * Charging type which is going to charge the car until
   * connector is disconnected from charger or the user doesn't 
   * have enough money on card.
   */
  const FULL_CHARGE = 'FULL_CHARGE';
}