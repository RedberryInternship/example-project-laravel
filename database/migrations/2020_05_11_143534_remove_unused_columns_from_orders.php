<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUnusedColumnsFromOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
           $table -> dropColumn(
									 [ 
													 'charge_fee', 
													 'charge_time', 
													 'confirmed', 
													 'confirm_date', 
													 'requested_already' 
									 ]
					 ); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
								$table -> string	( 'charge_fee' 	 			 ) -> nullable();
								$table -> string	( 'charge_time'				 ) -> nullable();
								$table -> boolean	( 'confirmed'		 			 ) -> nullable();
								$table -> string	( 'confirm_date'			 ) -> nullable();
								$table -> boolean	( 'requested_already'	 ) -> nullable();
        });
    }
}
