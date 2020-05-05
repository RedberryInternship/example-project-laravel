<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKilowattHourIntoKilowatts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kilowatts', function (Blueprint $table) {
            $table -> string( 'kilowatt_hour' ) -> nullable( true ) -> after('consumed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kilowatts', function (Blueprint $table) {
            $table -> dropColumn( 'kilowatt_hour' );
        });
    }
}
