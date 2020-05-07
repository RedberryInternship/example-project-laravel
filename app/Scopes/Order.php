<?php

namespace App\Scopes;

use  Illuminate\Database\Eloquent\Builder;
trait Order
{
   /**
     * Get confirmed orders.
     * 
     * @param   Builder
     * @return  Builder
     */
    public function scopeConfirmed( $query )
    {
        return $query -> where( 'confirmed', 1 );
    }

    /**
     * Get orders with confirmed payments.
     * 
     * @param   Builder
     * @return  Builder
     */
    public function scopeConfirmedPayments( $query )
    {
        return $query -> with([ 'payments' => function ( $q ) {
            return $q -> confirmed();
        }]);
    }

    /**
     * Get orders with confirmed payments(with user cards).
     * 
     * @param   Builder
     * @return  Builder
     */
    public function scopeConfirmedPaymentsWithUserCards( $query )
    {
        return $query -> with([ 'payments' => function ( $q ) {
            return $q -> confirmed() -> withUserCards();
        }]);
    }
}