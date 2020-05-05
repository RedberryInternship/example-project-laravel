<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeKilowatthourColumnInKilowatts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kilowatts', function (Blueprint $table) {
            $table -> renameColumn( 'kilowatt_hour', 'charging_power' );
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
            $table -> renameColumn( 'charging_power', 'kilowatt_hour' );
        });
    }
}
