<?php

namespace Redberry\GeorgianCardGateway\Responses;

class PaymentAvail extends Response
{

  public function __construct()
  {
    $this -> response = [
        'result' => [
            'code' => null,
            'desc' => null,
        ],
        'merchant-trx' => null,
        'purchase' => [
            'shortDesc' => null,
            'longDesc' => null,
            'account-amount' => [
                'id' => config('georgian-card-gateway.account_id'),
                'amount' => null,
                'currency' => 981,
                'exponent' => 2,    
            ],
        ],
    ];

    $this -> wrapper = 'payment-avail-response';
  }

  public function setResultCode( int $code )
  {
    $this -> response [ 'result' ][ 'code' ] = $code;
  }
  
  public function setResultDesc( string $desc )
  {
    $this -> response [ 'result' ][ 'desc' ] = $desc;
  }

  public function setMerchantTRX( string $merchant_trx_id )
  {
    $this -> response [ 'merchant-trx' ] = $merchant_trx_id; 
  }

  public function setPrimaryTrxPcid( string $primaryTrxPcid )
  {
    $this -> response [ 'primaryTrxPcid' ] = $primaryTrxPcid;
  }

  public function setPurchaseShortDesc( string $shortDesc )
  {
    $this -> response [ 'purchase' ][ 'shortDesc' ] = $shortDesc;
  }

  public function setPurchaseLongDesc( string $longDesc )
  {
    $this -> response [ 'purchase' ][ 'longDesc' ] = $longDesc;
  }

  public function setPurchaseAmount( float $amount )
  {
    $this -> response [ 'purchase' ][ 'account-amount' ][ 'amount' ] = $amount;
  }

  public function setTransactionTypeToCardRegister()
  {
    $this -> response [ 'transaction-type' ] = 'CardRegister';
  }
  
  public function setTransactionTypeToPayment()
  {
    $this -> response [ 'transaction-type' ] = 'Payment';
  }

  public function setCardPresentMode( bool $mode )
  {
    if( ! isset( $this -> response[ 'card' ]))
    {
      $this -> response[ 'card' ] = [];
    }

    $this -> response[ 'card' ][ 'present' ]  = $mode ? 'Y' : 'N';
  }

  public function setCardRef( string $ref )
  {
    if( ! isset( $this -> response[ 'card' ]))
    {
      $this -> response[ 'card' ] = [];
    }
    
    $this -> response[ 'card' ][ 'ref' ]  = $ref;
  }

}