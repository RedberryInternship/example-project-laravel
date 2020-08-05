<?php

namespace App\Facades;

/**
 * @method static bool isChargerFree( $charger_id )
 *
 * @see App\Library\Charging\Charger
 */

class SMS extends Facade
{
    protected static function resolveFacade()
    {
        return resolve('SMSProvider');
    }
}
