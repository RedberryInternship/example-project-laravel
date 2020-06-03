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
   * @return mixed
   */
  public function execute()
  {
    return redirect( $this -> buildUrl() );
  }

  /**
   * Set amount in tetris.
   * 100 = 1 GEL
   * 
   * @param   int $amount
   * @return  void
   */
  public function setAmount( int $amount ): void
  {
    $this -> data [ 'o.amount' ] = $amount;
  }

  /**
   * Set order id.
   * 
   * @param   string $orderId
   * @return  void
   */
  public function setOrderId( string $orderId ): void
  {
    $this -> data [ 'o.id' ] = $orderId;
  }

  /**
   * Set user id.
   * 
   * @param  string $userId
   * @return void
   */
  public function setUserId( string $userId ): void
  {
    $this -> data [ 'o.user_id' ] = $userId;
  }

  /**
   * Set user card id.
   * 
   * @param   string $userCardId
   * @return  void
   */
  public function setUserCardId( string $userCardId ): void
  {
    $this -> data [ 'o.user_card_id' ] = $userCardId;
  }

  /**
   * Enable preauth.
   * As of this moment I have no idea
   * what this means.
   * 
   * @return  void
   */
  public function enablePreauth(): void
  {
    $this -> data [ 'preauth' ] = 'Y';
  }

  /**
   * Pass additional data which will be present
   * in the last step success or fail.
   * 
   * @return void
   */
  public function passResultingData(array $data ): void
  {
    $this -> data [ 'back_url_f' ] .= '?';
    $this -> data [ 'back_url_s' ] .= '?';

    foreach( $data as $key => $value )
    {
      $this -> data [ 'back_url_f' ] .= $key . '=' . $value;;
      $this -> data [ 'back_url_s' ] .= $key . '=' . $value;
    }
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