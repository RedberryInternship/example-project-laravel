<?php

namespace App\Enums;

class Role extends Enum
{
  /** 
   * Regular app user. 
   */
  const REGULAR   = 'Regular';

  /**
   * Nova admin panel super admin.
   */
  const ADMIN     = 'Admin';

  /**
   * Partner business user.
   */
  const BUSINESS  = 'Business';

  /**
   * Payment user.
   */
  const PAYMENT   = 'Payment';
}