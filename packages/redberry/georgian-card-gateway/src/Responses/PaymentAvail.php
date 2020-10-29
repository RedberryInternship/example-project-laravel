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
            'longDesc'  => null,
            'account-amount' => [
                'id'        => config('georgian-card-gateway.account_id'),
                'amount'    => null,
                'currency'  => 981,
                'exponent'  => 2,
            ],
        ],
    ];

    $this -> wrapper = 'payment-avail-response';
  }

  /**
   * Set Result code.
   *
   * @example 1 => good to go.
   * @example 2 => stop.
   *
   * @param   int $code
   * @return  void
   */
  public function setResultCode( int $code ): void
  {
    $this -> response [ 'result' ][ 'code' ] = $code;
  }

  /**
   * Describe result.
   *
   * @example 1 => payment is available on order X
   * @example 2 => payment is not available on order X
   *
   * @param   string $desc
   * @return  void
   */
  public function setResultDesc( string $desc ): void
  {
    $this -> response [ 'result' ][ 'desc' ] = $desc;
  }

  /**
   * Set merchant transaction id of this specific transaction.
   *
   * @example 758843E9FDCB2AEA868EB175D534F082
   *
   * @param   string $merchantTrxId
   * @return void
   */
  public function setMerchantTRX( string $merchantTrxId ): void
  {
    $this -> response [ 'merchant-trx' ] = $merchantTrxId;
  }

  /**
   * Set primary transaction id.
   *
   * @param string $primaryTrixPcid
   */
  public function setPrimaryTrxPcid( string $primaryTrxPcid ): void
  {
    $this -> response [ 'primaryTrxPcid' ] = $primaryTrxPcid;
  }

  /**
   * Describe purchase shortly.
   *
   * @param   string $shortDesc
   * @return  void
   */
  public function setPurchaseShortDesc( string $shortDesc ): void
  {
    $this -> response [ 'purchase' ][ 'shortDesc' ] = $shortDesc;
  }

  /**
   * Describe purchase more thoroughly.
   *
   * @param   string $longDesc
   * @return  void
   */
  public function setPurchaseLongDesc( string $longDesc ): void
  {
    $this -> response [ 'purchase' ][ 'longDesc' ] = $longDesc;
  }

  /**
   * Set purchase amount.
   *
   * @param   int $amount
   * @return  void
   */
  public function setPurchaseAmount( int $amount ): void
  {
    $this -> response [ 'purchase' ][ 'account-amount' ][ 'amount' ] = $amount;
  }

  /**
   * Set account id.
   * optional, necessary if there are many pos terminals.
   *
   * @param  string $accountId
   * @return void
   */
  public function setAccountId( string $accountId ): void
  {
    $this -> response [ 'purchase' ][ 'account-amount' ][ 'id' ] = $accountId;
  }
}
