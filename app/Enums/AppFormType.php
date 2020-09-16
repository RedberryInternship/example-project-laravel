<?php

namespace App\Enums;

class AppFormType extends Enum
{
  /**
   * State that corresponds registering user.
   */
  const REGISTER      = 'registers';

  /**
   * Charger that is offline. and can't charge car.
   */
  const PASSWORD_RESET    = 'password_reset'; 

  /**
   * Charger that is online but also
   * is charging at the moment.
   */
  const PHONE_NUMBER_CHANGE    = 'phone_change';
}