<?php

namespace App\Enums;

class ContractMethod extends Enum
{
  /**
   * Client pays fix price each month.
   */
  const FIX_PRICE  = 'FIX_PRICE';

  /**
   * Client pays percentage of transactions.
   */
  const PERCENTAGE = 'PERCENTAGE';
}