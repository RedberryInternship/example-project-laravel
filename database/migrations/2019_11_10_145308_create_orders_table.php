<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('old_id');
            $table->integer('charger_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('connector_type_id')->nullable();
            $table->integer('charging_type_id')->nullable();
            $table->boolean('finished');
            $table->boolean('paid');
            $table->string('charge_fee');
            $table->string('charge_time');
            $table->boolean('refunded');
            $table->string('target_price');
            $table->string('uuid');
            $table->boolean('requested_already');
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
        Schema::dropIfExists('orders');
    }
}
