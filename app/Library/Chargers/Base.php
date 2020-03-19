<?php

namespace App\Library\Chargers;

class Base {

    protected $protocol;
    protected $ip;
    protected $url      = '';

    protected $guzzleClient;

    public function __construct($protocol, $ip, $guzzleClient)
    {

        $this -> url = $protocol . '://' . $ip;
        $this -> guzzleClient = $guzzleClient;
    }

    protected function sendRequest($serviceUrl)
    {
        $response = $this -> guzzleClient -> request('GET', $serviceUrl);

        return $this -> parseResponse($response);
    }

    private function parseResponse($response){

        $data = [
            'status-code' => $response -> getStatusCode(),
            'body' => json_decode($response -> getBody() -> getContents(), true),
        ];  

        return $data;
    }

    protected function isOk($response){
        if($response['status-code'] == 200){
            return true;
        }

        return false;
    }
}