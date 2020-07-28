<?php

namespace Redberry\GeorgianCardGateway;

class Refund
{
  /**
   * Base url.
   * 
   * @var string $url
   */
  private $url;

  /**
   * Refund data.
   * 
   * @var array $data
   */
  private $data;


  /**
   * Build refund instance.
   * 
   * @return self
   */
  public static function build()
  {
    return new self;
  }

  /**
   * Construct url and data.
   */
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

  /**
   * Make transaction refund.
   * 
   * @return void
   */
  public function execute()
  {
    $url  = $this -> buildUrl();
    $ch   = curl_init( $url );
    
    curl_setopt(  $ch, CURLOPT_RETURNTRANSFER, true );
    curl_exec  (  $ch                               );
  }

  /**
   * Set transaction id of which the refund should be made.
   * 
   * @param  string $trxId
   * @return self
   */
  public function setTrxId( string $trxId ): Refund
  {
    $this -> data [ 'trx_id' ] = $trxId;
    
    return $this;
  }

  /**
   * Set RRN of the transaction of which the refund should be made.
   */
  public function setRRN( string $RRN ): Refund
  {
    $this -> data [ 'p.rrn' ] = $RRN;

    return $this;
  }

  /**
   * Set refund amount.
   * 
   * @param  int $amount 
   * @example 100 = 1 GEL
   * 
   * @return self
   */
  public function setAmount( int $amount ): Refund
  {
    $this -> data [ 'amount' ] = $amount;

    return $this;
  }

  /**
   * Build refund url.
   * 
   * @return string
   */
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