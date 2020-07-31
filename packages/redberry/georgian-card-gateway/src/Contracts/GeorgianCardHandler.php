<?php

namespace Redberry\GeorgianCardGateway\Contracts;

use Redberry\GeorgianCardGateway\Responses\PaymentAvail;
use Redberry\GeorgianCardGateway\Responses\RegisterPayment;

interface GeorgianCardHandler
{
  /**
   * First phase of the payment.
   * Check if you want to make transaction.
   * 
   * @param   PaymentAvail $data
   * @return  string|null
   */
  function paymentAvail( PaymentAvail $data ): PaymentAvail;

  /**
   * Register payment operation.
   * Make necessary operations,
   * for example add user card, 
   * payments record, etc.
   * 
   * @param   RegisterPayment  $data
   * @return  void
   */
  function registerPayment( RegisterPayment $data ): RegisterPayment;

  /**
   * Success method will be executed if
   * transaction is to end successfully.
   * 
   * @return mixed
   */
  function success();

  /**
   * Failed method will be executed if
   * transaction is to end with failure.
   * 
   * @return mixed
   */
  function failure();
}