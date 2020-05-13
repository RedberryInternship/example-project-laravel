<?php

namespace App\Enums;

class GeneralError extends Enum
{
  /**
   * Something went wrong while processing data.
   */
  const SOMETHING_WENT_WRONG          = 'SOMETHING_WENT_WRONG';

  /**
   * Can't make finish charging call because 
   * charging is already finished.
   */
  const CHARGING_ALREADY_FINISHED     = 'CHARGING_ALREADY_FINISHED';

  /**
   * Can't confirm if transaction is really finished.
   */
  const CANT_CONFIRM_CHARGING_FINISH  = 'CANT_CONFIRM_CHARGING_FINISH';

}