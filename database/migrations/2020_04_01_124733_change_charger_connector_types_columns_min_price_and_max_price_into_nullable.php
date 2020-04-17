<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeChargerConnectorTypesColumnsMinPriceAndMaxPriceIntoNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('charger_connector_types', function (Blueprint $table) {
            $table->float('min_price', 5, 2) -> nullable(true) -> change();
            $table->float('max_price', 5, 2) -> nullable(true) -> change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('charger_connector_types', function (Blueprint $table) {
            $table->float('min_price', 5, 2) -> nullable(false) -> change();
            $table->float('max_price', 5, 2) -> nullable(false) -> change();
        });
    }
}
