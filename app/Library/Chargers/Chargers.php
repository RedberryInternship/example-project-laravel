<?php

namespace App\Library\Chargers;

use \GuzzleHttp\Client;

class Chargers
{
    /**
     * Get All Active Chargers from Misha's Service.
     */
    public function get()
    {
        $client   = new Client();
        $response = $client -> request('GET', 'http://13.92.63.164/es-services/mobile/ws/chargers');

        echo '<pre>';

        echo $response -> getStatusCode(); // 200
        echo $response -> getHeaderLine('content-type'); // 'application/json; charset=utf8'
        
        print_r($response -> getBody());
    }
}
