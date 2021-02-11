<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrderChargingPowers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema :: create('charging_powers', function(Blueprint $table) {
            $table -> increments('id');
            $table -> bigInteger('order_id') -> nullable();
            $table -> float('charging_power') -> default(0);
            $table -> string('tariffs_power_range') -> nullable();
            $table -> string('tariffs_daytime_range') -> nullable();
            $table -> float('tariff_price');
            $table -> string('start_at');
            $table -> string('end_at') -> nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema :: dropIfExists('charging_powers');
    }
}
