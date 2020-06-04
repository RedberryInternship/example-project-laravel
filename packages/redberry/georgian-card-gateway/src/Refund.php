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
      'p.rnn'   => null,
      'amount'  => null,
    ];
  }

  public function execute()
  {
    $url = $this -> buildUrl();
    return redirect( $url );
  }

  public function setTrxId( string $trxId )
  {
    $this -> data [ 'trx_id' ] = $trxId;
  }

  public function setRRN( string $RRN )
  {
    $this -> data [ 'p.rrn' ] = $RRN;
  }

  public function setAmount( int $amount )
  {
    $this -> data [ 'amount' ] = $amount;
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