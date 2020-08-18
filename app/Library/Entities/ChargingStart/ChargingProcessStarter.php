<?php

namespace App\Library\Entities\ChargingStart;

use App\Facades\Charger;
use Illuminate\Support\Facades\Log;
use App\Library\DataStructures\StartTransaction as StartTransactionResponse;

class ChargingProcessStarter
{
  /**
   * Real charger id.
   * 
   * @var int $chargerId
   */
  private $chargerId;

  /**
   * Real charger connector type id.
   * 
   * @var int $connectorTypeId
   */
  private $connectorTypeId;

  /**
   * Return new instance.
   * 
   * @return self
   */
  public static function instance(): self
  {
    return new self;
  }

  /**
   * Set Real charger id.
   * 
   * @param  int $chargerId
   * @return Start
   */
  public function setChargerId( int $chargerId ): self
  {
    $this -> chargerId = $chargerId;
    return $this;
  }

  /**
   * Set Real charger connector type id.
   * 
   * @param  int $connectorTypeId
   * @return Start
   */
  public function setConnectorTypeId( int $connectorTypeId ): self
  {
    $this -> connectorTypeId = $connectorTypeId;
    return $this;
  }

  /**
   * Start charging process.
   * 
   * @return StartTransactionResponse
   */
  public function execute(): StartTransactionResponse
  {
    $transactionID = $this -> makeStartChargingRequest();

    if( $transactionID == -1 || $transactionID == -101 )
    {
      $isFree = Charger :: isChargerFree( $this -> chargerId );

      if( $isFree )
      {
        return $this -> transactionCanceled();
      }
      else
      {
        return $this -> transactionNotConfirmed();
      }
    }

    return $this -> transactionStartedSuccessfully( $transactionID );
  }

  /**
   * make start charging request.
   * 
   * @return int|string $transactionID
   */
  private function makeStartChargingRequest()
  {
      $transactionID = Charger :: start(
          $this -> chargerId, 
          $this -> connectorTypeId,
      );

      $log = 'Charging Start | ChargerID - '. $this -> chargerId . ' | ConnectorTypeId - ' . $this -> connectorTypeId . ' | Result - ' . $transactionID;
      
      Log :: channel( 'start-charging' ) -> info( $log );

      return $transactionID;
  }

  /**
   * transaction is canceled.
   * 
   * @return StartTransactionResponse
   */
  public function transactionCanceled(): StartTransactionResponse
  {
    $response = new StartTransactionResponse;
    $response -> setTransactionID( -1 );
    $response -> setTransactionStatus( StartTransactionResponse :: FAILED );

    return $response;
  }

  /**
   * Transaction is not confirmed,
   * we don't have transaction id.
   * 
   * @return StartTransactionResponse
   */
  public function transactionNotConfirmed()
  {
    $response = new StartTransactionResponse;
    $response -> setTransactionID( -1 );
    $response -> setTransactionStatus( StartTransactionResponse :: NOT_CONFIRMED );

    return $response;
  }

  /**
   * Transaction started successfully, and we
   * have transaction id.
   * 
   * @param  int|string $transactionID
   * @return StartTransactionResponse
   */
  public function transactionStartedSuccessfully( $transactionID ): StartTransactionResponse
  {
    $response = new StartTransactionResponse;
    $response -> setTransactionID( $transactionID );
    $response -> setTransactionStatus( StartTransactionResponse :: SUCCESS );

    return $response;
  }
}