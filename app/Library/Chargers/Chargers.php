<?php

namespace App\Library\Chargers;

use \GuzzleHttp\Client;

class Chargers
{
    protected $protocol = 'http';
    protected $ip       = '13.92.63.164';
    protected $url      = '';

    protected $guzzleClient;

    public function __construct(Client $guzzleClient)
    {
        $this -> url = $this -> protocol . '://' . $this -> ip;

        $this -> guzzleClient = $guzzleClient;
    }

    /**
     * Get All Active Chargers from Misha's Service.
     *
     * @param $chargerID = null
     */
    public function get($chargerID = null)
    {
        $serviceUrl = $this -> url . '/es-services/mobile/ws/chargers';
        if (isset($chargerID) && $chargerID)
        {
            $serviceUrl = $this -> url . '/es-services/mobile/ws/charger/info/' . $chargerID;
        }

        $this -> sendRequest($serviceUrl);
    }

    /**
     * Activate Charger.
     * 
     * @param $chargerID
     */
    public function activate($chargerID)
    {
        $serviceUrl = $this -> url . ':12801/api/simulator/cp/add/'. $chargerID;

        $this -> sendRequest($serviceUrl);
    }

    /**
     * CP (I have no idea what it means yet) Charger.
     * 
     * @param $chargerID
     */
    public function cp($chargerID)
    {
        $serviceUrl = $this -> url . ':12801/api/simulator/cp/type/'. $chargerID . '/SIMULATOR_KEBA';

        $this -> sendRequest($serviceUrl);
    }

    /**
     * Send GuzzleHttp Request.
     * 
     * @param $serviceUrl
     */
    protected function sendRequest($serviceUrl)
    {
        $response = $this -> guzzleClient -> request('GET', $serviceUrl);

        echo '<pre>';

        echo $response -> getStatusCode(); // 200
        echo $response -> getHeaderLine('content-type'); // 'application/json; charset=utf8'
        
        print_r($response -> getBody());
    }
}
