<?php

namespace Redberry\GeorgianCardGateway;

class Transaction
{
  /**
   * Georgian card base url.
   * 
   * @var string $url
   */
  private $url;

  /**
   * Get request data which will be passed.
   * 
   * @var array $data
   */
  private $data;

  /**
   * Set initial private parameters.
   */
  public function __construct()
  {
    $this -> url = 'https://3dacq.georgiancard.ge/payment/start.wsm?';

    $this -> data = [
      'lang'        => 'KA',
      'preauth'     => 'N',
      'merch_id'    => config('georgian-card-gateway.merchant_id'),
      'page_id'     => config('georgian-card-gateway.page_id'),
      'account_id'  => config('georgian-card-gateway.account_id'),
      'back_url_f'  => config('georgian-card-gateway.back_url_f'),
      'back_url_s'  => config('georgian-card-gateway.back_url_s'),
      'ccy'         => config('georgian-card-gateway.ccy'),
    ];
  }

  /**
   * Build url and redirect to
   * georgian card page.
   * 
   * @return void
   */
  public function execute(): void
  {
    $url  = $this -> buildUrl();
    $ch   = curl_init( $url );
    
    curl_setopt(  $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt(  $ch, CURLOPT_FOLLOWLOCATION, true );
    curl_exec  (  $ch                               );
  }

  /**
   * Set amount in tetris.
   * 100 = 1 GEL
   * 
   * @param   int $amount
   * @return  Transaction
   */
  public function setAmount( int $amount ): Transaction
  {
    $this -> data [ 'o.amount' ] = $amount;
    
    return $this;
  }

  /**
   * Set order id.
   * 
   * @param   string $orderId
   * @return  Transaction
   */
  public function setOrderId( string $orderId ): Transaction
  {
    $this -> data [ 'o.id' ] = $orderId;
    return $this;
  }

  /**
   * Set user id.
   * 
   * @param  string $userId
   * @return Transaction
   */
  public function setUserId( string $userId ): Transaction
  {
    $this -> data [ 'o.user_id' ] = $userId;
    return $this;
  }

  /**
   * Set user card id.
   * 
   * @param   string $userCardId
   * @return  Transaction
   */
  public function setUserCardId( string $userCardId ): Transaction
  {
    $this -> data [ 'o.user_card_id' ] = $userCardId;
    return $this;
  }

  /**
   * Set transaction type to register.
   * 
   * @return Transaction
   */
  public function shouldSaveCard(): Transaction
  {
    $this -> data [ 'o.type' ] = 'register';
    
    return $this;
  }

  /**
   * Set additional parameters.
   * 
   * @param   string $key
   * @param   mixed  $value
   * @return  Transaction
   */
  public function set( string $key, $value ): Transaction
  {
    $this -> data[ 'o.' . $key ] = $value;
    
    return $this;
  }

  /**
   * Enable preauth.
   * As of this moment I have no idea
   * what this means.
   * 
   * @return  Transaction
   */
  public function enablePreauth(): Transaction
  {
    $this -> data [ 'preauth' ] = 'Y';

    return $this;
  }

  /**
   * Pass additional data which will be present
   * in the last step success or fail.
   * 
   * @return Transaction
   */
  public function passResultingData(array $data ): Transaction
  {
    $this -> data [ 'back_url_f' ] .= '?';
    $this -> data [ 'back_url_s' ] .= '?';

    foreach( $data as $key => $value )
    {
      $this -> data [ 'back_url_f' ] .= '&' . $key . '=' . $value;
      $this -> data [ 'back_url_s' ] .= '&' . $key . '=' . $value;
    }

    $this -> data [ 'back_url_f' ] = urlencode( $this -> data [ 'back_url_f' ] );
    $this -> data [ 'back_url_s' ] = urlencode( $this -> data [ 'back_url_s' ] );

    return $this;
  }

  /**
   * Set account id when there are several pos terminals.
   * 
   * @param string $accountId
   * @return Transaction
   */
  public function setAccountId( string $accountId ): Transaction
  {
    $this -> data [ 'account_id' ] = $accountId;
    return $this;
  }

  /**
   * Build get request url.
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