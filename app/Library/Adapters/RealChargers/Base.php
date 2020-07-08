<?php

namespace App\Library\Adapters\RealChargers;

use App\Exceptions\Charger\MishasBackException;
use Exception;

class Base 
{
    /**
     * Misha's protocol.
     * 
     * @var string $protocol
     */
    protected $protocol;

    /**
     * Misha's ip.
     * 
     * @var string $ip
     */
    protected $ip;

    /**
     * Misha's url.
     * 
     * @var string $url
     */
    protected $url;


    /**
     * GuzzleClient.
     * 
     * @var \GuzzleHttp\Client $guzzleClient
     */
    protected $guzzleClient;


    /**
     * Instantiate config parameters.
     * 
     * @return void
     */
    public function __construct( $protocol, $ip, $guzzleClient )
    {
        $this -> guzzleClient  = $guzzleClient;
        $this -> url           = $protocol . '://' . $ip;
    }

    /**
     * Send request with guzzle and parse response.
     * 
     * @param string $serviceUrl
     * @return array
     */
    protected function sendRequest( $serviceUrl )
    {
        try
        {
            $response = $this -> guzzleClient -> request( 'GET', $serviceUrl );
        }
        catch( Exception $e )
        {
            throw new MishasBackException();
        }

        return $this -> parseResponse( $response );
    }

    /**
     * Parse request response.
     * 
     * @param GuzzleHttp\Psr7\Response $response
     * @return array
     */
    private function parseResponse( $response )
    {
        $data = [
            'status-code'  => $response -> getStatusCode(),
            'body'         => $response -> getBody() -> getContents(),
        ];  

        return $data;
    }

    /**
     * Determine if response status is ok.
     * 
     * @param array $response
     * @return bool
     */
    protected function isOk($response)
    {
        if($response['status-code'] == 200)
        {
            return true;
        }

        return false;
    }
}