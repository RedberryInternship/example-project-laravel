<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChargeFastChargingPricesMinutesType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fast_charging_prices', function (Blueprint $table) {
            $table -> dropColumn(
                [
                    'start_minutes',
                    'end_minutes',
                ]
            );
        });
        
        Schema::table('fast_charging_prices', function (Blueprint $table) {
            $table -> unsignedBigInteger( 'end_minutes' )   -> default( 0 ) -> after( 'id' );
            $table -> unsignedBigInteger( 'start_minutes' ) -> default( 0 ) -> after( 'id' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fast_charging_prices', function (Blueprint $table) {
            $table -> string( 'start_minutes' ) -> change();
            $table -> string( 'end_minutes' )   -> change();
        });
    }
}
