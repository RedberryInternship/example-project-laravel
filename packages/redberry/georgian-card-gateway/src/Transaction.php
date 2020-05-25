<?php

namespace Redberry\GeorgianCardGateway;

class Transaction
{
  private $url;
  private $data;

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

  public function execute()
  {
    return redirect( $this -> buildUrl() );
  }

  public function setAmount( float $amount )
  {
    $this -> data [ 'o.amount' ] = $amount;
  }

  public function setOrderId( string $orderId )
  {
    $this -> data [ 'o_order_id' ] = $orderId;
  }

  public function enablePreauth()
  {
    $this -> data [ 'preauth' ] = 'Y';
  }

  private function buildUrl()
  {
    $url = $this -> url;

    foreach( $this -> data as $key => $value )
    {
      $url .= '&' . $key . '=' . $value;
    }

    return $url;
  }
}