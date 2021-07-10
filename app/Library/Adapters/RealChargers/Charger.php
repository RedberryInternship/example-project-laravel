<?php

namespace App\Library\Adapters\RealChargers;

use Illuminate\Support\Facades\Log;
use App\Exceptions\Charger\FindChargerException;
use App\Exceptions\Charger\StopChargingException;
use App\Exceptions\Charger\StartChargingException;
use App\Exceptions\Charger\RealChargersBackException;
use App\Exceptions\Charger\ChargerTransactionInfoException;
use App\Exceptions\Charger\TransactionAlreadyFinishedException;

class Charger extends Base
{
    /**
     * Response parameter.
     * 
     * @var mixed $response
     */
    private $response;

    /**
     * For Testing Purposes.
     * 
     * @var string $realChargersBaseUrl
     */
    private $realChargersBaseUrl = 'https://chargers.e-space.ge:8443';

    /**
     * Get all the chargers info from Misha's back
     * 
     * @return array
     */
    public function all()
    {
        Log :: channel( 'request-charger' ) -> info( 'GET_ALL_CHARGERS' );
        $service_url = $this -> url . '/es-services/mobile/ws/chargers';
        $result      = $this -> fetchData( $service_url );
        
        switch( $result -> status )
        {
            case 0:
                return $result -> data -> chargers;
            default:
                throw new FindChargerException( 
                    'Chargers couldn\'t be retrieved from Misha\'s DB.',
                        500,
                        );
        }
    }


    /**
     * Get All the active chargers charger_id
     * 
     * @return array
     */
    public function getFreeChargersIds()
    {    
        $free_chargers_ids = [];
        $all_chargers_info = $this -> all();
   
        foreach($all_chargers_info as $single_charger_info)
        {
            if($single_charger_info -> status == 0)
            {
                $free_chargers_ids []= $single_charger_info -> id;
            }
        }
        
        return $free_chargers_ids;
    }

    /**
     * Find one charger in Misha's DB.
     * 
     * @param int $charger_id
     * @return object
     */
    public function find($charger_id)
    {
        # if( $charger_id == 27833 )
        # {
        #     $this -> url = $this -> realChargersBaseUrl;
        # }

        Log :: channel( 'request-charger' ) -> info( 'GET_ONE_CHARGER' );

        $service_url = $this -> url 
                        . '/es-services/mobile/ws/charger/info/' 
                        . $charger_id;
        
        $result      = $this -> fetchData( $service_url );
        
        switch( $result -> status )
        {
            case 0:
                return $result -> data;
            default:
                throw new FindChargerException();
        }
     }
    
    /**
     * Determine if charger is dead.
     * 
     * @param int $charger_id
     * @return object
     */
    public function isDead($charger_id)
    {
        Log :: channel( 'request-charger' ) -> info( 'IS_DEAD' );
        $result = $this -> find($charger_id);   
        return $result -> status == 0;
     }

     /**
      * Find out if this specific charger is free
      * 
      * @param int $charger_id
      * @return bool 
      */
      public function isFree($charger_id)
      {
        Log :: channel( 'request-charger' ) -> info( 'IS_FREE' );
        $result = $this -> find($charger_id);
        
        return $result -> status == 0;
      }
    
    /**
     * Start Charging request to Misha's Back.
     * 
     * @param int $charger_id
     * @param int $connector_id
     * @return array<object>
     */
     public function start($charger_id, $connector_id)
     {
        # if( $charger_id == 27833 )
        # {
        #     $this -> url = $this -> realChargersBaseUrl;
        # }
        

        $text = 'START | CHARGER ID - ' . $charger_id . ' | CONNECTOR - ' . $connector_id;
        Log :: channel( 'request-charger' ) -> info( $text );

        $service_url = $this -> url 
                        . '/es-services/mobile/ws/charger/start/'
                        . $charger_id .'/' 
                        . $connector_id;
        
        $result      = $this -> fetchData($service_url);

        switch($result -> status)
        {
            case -1:
                throw new StartChargingException(
                    'Charger of charger_id ' . $charger_id . ' is offline!', 
                    400,
                );

            case -2:
                throw new StartChargingException(
                    'No such charger with charger_id of ' . $charger_id . '.',
                    400,
                );

            case -100:
                return -100;
            case -101:
                return -101;
            case 0:
                return $result -> data;
            default:
                throw new RealChargersBackException();
        }
    }
    
    /**
     * Stop Charging request to Misha's Back.
     * 
     * @param int $charger_id
     * @param int $transaction_id
     * @return object
     */
    public function stop( $charger_id, $transaction_id )
    {
        # if( $charger_id == 27833 )
        # {
        #     $this -> url = $this -> realChargersBaseUrl;
        # }

        $text = 'STOP | CHARGER ID - ' . $charger_id . ' | TRANSACTION - ' . $transaction_id;
        Log :: channel( 'request-charger' ) -> info( $text );

        $service_url = $this -> url 
                        . '/es-services/mobile/ws/charger/stop/'
                        . $charger_id .'/' 
                        . $transaction_id;
        
        $result      = $this -> fetchData( $service_url );
        
        switch( $result -> status )
        {
            case -100:
                throw new TransactionAlreadyFinishedException();
            case 0:
                return $result -> data;
            default:
                throw new StopChargingException();
        }
    }

    /**
     * Get transaction info from Misha's DB.
     * 
     * @param int $id
     * @return object
     */
    public function transactionInfo( $id )
    {
        /** This Code Should be deleted later on. I am ashamed that Im coding it. */

        # $order = \App\Order :: with( 'charger_connector_type.charger' ) 
        #     -> where( 'charger_transaction_id', $id ) 
        #     -> first()
        # if( $order )
        # {
        #     $charger_id = $order -> getCharger() -> charger_id;

        #     if( $charger_id == 27833 )
        #     {
        #         $this -> url = $this -> realChargersBaseUrl;
        #     }
        # }
        # else
        # {
        #     $this -> url = $this -> realChargersBaseUrl;
        # }
        
        /** Ends here. */

        $text = 'TRANSACTION_INFO | TRANSACITON_ID - ' . $id;
        Log :: channel( 'request-charger' ) -> info( $text );

        $service_url = $this -> url 
                        . '/es-services/mobile/ws/transaction/info/'
                        . $id;
        
        $result = $this -> fetchData( $service_url );
        
        switch( $result -> status )
        {
            case -2:
                throw new ChargerTransactionInfoException(
                    'Charger transaction info not found.',
                    404,
                );
            case 0:
                return $result -> data;
            default:
                throw new ChargerTransactionInfoException();
        }
    }
  
    /**
     * Fetch data and set response parameter.
     * 
     * @param string $service_url
     * @return object
     */
    private function fetchData($service_url)
    {
        $response = $this -> sendRequest($service_url);

        if($this -> isOk( $response ))
        {
            $this -> setResponse(
                json_decode( $response[ 'body' ] )
            );
        }
        else
        {
            throw new RealChargersBackException();
        }

        return $this -> response;
    }

    /**
     * Set response parameter from request response.
     * 
     * @param object $data
     * @return void
     */
    private function setResponse( $data = null )
    {
        $this -> response = $data; 
    }
}
