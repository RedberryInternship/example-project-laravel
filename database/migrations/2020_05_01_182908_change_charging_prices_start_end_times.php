<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeChargingPricesStartEndTimes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('charging_prices', function (Blueprint $table) {
            $table -> dropColumn(
                [
                    'min_kwt',
                    'max_kwt',
                ]
            );
        });

        Schema::table('charging_prices', function (Blueprint $table) {
            $table -> unsignedBigInteger( 'max_kwt' )   -> default( 0 ) -> after( 'id' );
            $table -> unsignedBigInteger( 'min_kwt' )   -> default( 0 ) -> after( 'id' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('charging_prices', function (Blueprint $table) {
            $table -> string( 'start_time' )    -> after( 'max_kwt' );
            $table -> string( 'end_time' )      -> after( 'max_kwt' );
        });
    }
}
