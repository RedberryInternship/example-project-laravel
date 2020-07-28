<?php

namespace Redberry\GeorgianCardGateway\Controllers;

use Redberry\GeorgianCardGateway\Library\RegisterPaymentResponder;
use Redberry\GeorgianCardGateway\Library\PaymentAvailResponder;
use App\Http\Controllers\Controller;

class ResponseController extends Controller
{
    /**
     * Disable debugbar if it exists.
     */
    public function __construct()
    {
        if( app() -> bound( 'debugbar' ) )
        {
            resolve( 'debugbar' ) -> disable();
        }
    }

    /**
     * Set payment avail response.
     * 
     * @return xml
     */
    public function paymentAvailResponse()
    {
        return PaymentAvailResponder :: build() -> respond();
    }

    /**
     * Register payment response.
     * 
     * @return xml
     */
    public function registerPaymentResponse()
    {
        return RegisterPaymentResponder :: build() -> respond();
    }
}
