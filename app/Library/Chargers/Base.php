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
        $this -> displayParsedResponse($response);
    }

    private function displayParsedResponse($response){

        $data = [
            'status-code' => $response -> getStatusCode(), // 200
            'content-type' => $response -> getHeaderLine('content-type'), // 'application/json; charset=utf8'
            'body' => json_decode($response -> getBody() -> getContents(), true),
        ];

        dd($data);

    }
}