<?php

namespace App\Library\Interactors;

use App\Library\Entities\SMS\Twilio;
use App\Library\Entities\SMS\Maradit;

class SMS
{
    /**
     * Send SMS.
     * 
     * @param $provider
     * @param $data
     */
    public static function sendSms($data)
    {
        $phoneNumber = str_replace('+', '', $data['phoneNumber']);

        $provider    = substr($phoneNumber, 0, 3) == '995' ? (new Maradit) : (new Twilio);

        return $provider -> sendSms($data);
    }
}
