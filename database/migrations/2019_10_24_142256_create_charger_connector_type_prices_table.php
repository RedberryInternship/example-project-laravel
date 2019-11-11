<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChargerConnectorTypePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charger_connector_type_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('charger_connector_type_id');
            $table->string('price');
            $table->text('start_time');
            $table->text('end_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('charger_connector_type_prices');
    }
}
