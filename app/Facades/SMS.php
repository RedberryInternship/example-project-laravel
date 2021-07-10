<?php

namespace App\Facades;

class SMS extends Facade
{
    protected static function resolveFacade()
    {
        return resolve('SMSProvider');
    }
}
