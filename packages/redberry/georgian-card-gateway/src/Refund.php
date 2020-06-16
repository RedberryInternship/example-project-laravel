<?php

namespace Redberry\GeorgianCardGateway;

class Refund
{
  private $url;
  private $data;

  public function __construct()
  {
    $merchantId   = config( 'georgian-card-gateway.merchant_id'     );
    $merchantPass = config( 'georgian-card-gateway.refund_api_pass' );

    $this -> url = 'https://'
      . $merchantId .':'. $merchantPass 
      .'@3dacq.georgiancard.ge/merchantapi/refund?';
    
    $this -> data = [
      'trx_id'  => null,
      'p.rrn'   => null,
      'amount'  => null,
    ];
  }

  public function execute()
  {
    $url  = $this -> buildUrl();
    $ch   = curl_init( $url );
    
    curl_setopt(  $ch, CURLOPT_RETURNTRANSFER, true );
    curl_exec  (  $ch                               );
  }

  public function setTrxId( string $trxId ): Refund
  {
    $this -> data [ 'trx_id' ] = $trxId;
    
    return $this;
  }

  public function setRRN( string $RRN ): Refund
  {
    $this -> data [ 'p.rrn' ] = $RRN;

    return $this;
  }

  public function setAmount( int $amount ): Refund
  {
    $this -> data [ 'amount' ] = $amount;

    return $this;
  }

  public function buildUrl()
  {
    $url = $this -> url;
    
    foreach( $this -> data as $key => $value )
    {
      $url .= '&' . $key . '=' . $value;
    }

    return $url;
  }
}