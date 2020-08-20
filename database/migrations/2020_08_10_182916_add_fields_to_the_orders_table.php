<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToTheOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table -> string ( 'real_end_date'  ) -> nullable() -> after( 'charging_status_change_dates' );
            $table -> string ( 'real_start_date') -> nullable() -> after( 'charging_status_change_dates' );
            
            $table -> string ( 'address'        ) -> nullable() -> after( 'comment' );
            $table -> float  ( 'charge_power'   ) -> nullable() -> after( 'comment' );
            $table -> integer( 'duration'       ) -> nullable() -> after( 'comment' );
            $table -> float  ( 'penalty_fee'    ) -> nullable() -> after( 'comment' );
            $table -> float  ( 'charge_price'   ) -> nullable() -> after( 'comment' );
            $table -> string ( 'start_date'     ) -> nullable() -> after( 'comment' );
            $table -> string ( 'charger_name'   ) -> nullable() -> after( 'comment' );
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
            $table -> dropColumn(
                [ 
                    'charger_name'  , 
                    'start_date'    , 
                    'charge_price'  , 
                    'penalty_fee'   , 
                    'duration'      , 
                    'charge_power'  , 
                    'address'       ,
                ]
            );
        });
    }
}
