<?php

namespace App\Enums;

class OrderStatus extends Enum
{
  /**
   * ONLY FOR LVL2 CHARGERS 
   * 
   * We send charge request to Misha and charging technically
   * started, but we won't account it as charging time until
   * enough kilowatts are going to be flowing.
   */
  const INITIATED      = 'INITIATED';

  /**
   * Car is charging so we are gonna account 
   * the charged kilowatts or the spent time 
   * according pricing and charging method and connector type.
   */
  const CHARGING       = 'CHARGING';

  /**
   * ONLY FOR LVL2 CHARGERS
   * 
   * When car is charged but connector is not disconnected
   * and also user is not ON_FINE for the time being.
   */
  const CHARGED        = 'CHARGED';

  /**
   * When charging type is BY_AMOUNT
   * and all the money is already
   * used up.
   */
  const USED_UP        = 'USED_UP';

  /**
   * Something happened, car is not being charged with the kilowatts
   * and transaction is not finished and it will be ON_HOLD until
   * we get the information that it has continued charging or else
   * got finished.
   */
  const ON_HOLD        = 'ON_HOLD';
  
  /**
   * ONLY FOR LVL2 CHARGERS 
   * 
   * Charging is finished but the user did not plugged the connector 
   * off the charger.
   * and he/she is going to pay accordingly.
   */
  const ON_FINE        = 'ON_FINE';

  /**
   * Transaction is finished.
   */
  const FINISHED       = 'FINISHED';

  /**
   * Transaction is not confirmed. if on start-charging request
   * we'll have success status becomes INITIATED or CHARGING
   * or else it stays NOT_CONFIRMED.
   */
  const NOT_CONFIRMED  = 'NOT_CONFIRMED';

  /**
   * Transaction is canceled due to miscommunication.
   */
  const CANCELED       = 'CANCELED';

  /**
   * Transaction is rejected to to trying charging without
   * plugging to connector.
   */
  const UNPLUGGED      = 'UNPLUGGED';

  /**
   * Transaction can't continue because there is 
   * not enough money on user card.
   */
  const BANKRUPT       = 'BANKRUPT';

  /**
   * Transaction can't continue because of the
   * payment failure.
   */
  const PAYMENT_FAILED = 'PAYMENT_FAILED';

  /**
   * Charger is not switched to application mode.
   */
  const NON_APP_MODE   = 'NON_APP_MODE';
}