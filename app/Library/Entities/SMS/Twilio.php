<?php

namespace App\Library\Entities\SMS;

use Twilio as TwilioProvider;

class Twilio
{
    /**
     * Send SMS.
     * 
     * @param $data
     */
    public function sendSms($data)
    {
        TwilioProvider::message($data['phoneNumber'], $data['message']);
    }
}
