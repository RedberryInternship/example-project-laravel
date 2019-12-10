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
            $table->integer('charger_type_id')->nullable();
            $table->integer('charging_type_id')->nullable();
            $table->boolean('finished')->nullable();
            $table->string('charge_fee')->nullable();
            $table->string('charge_time')->nullable();
            $table->integer('charger_transaction_id')->nullable();
            $table->boolean('confirmed')->nullable();
            $table->string('confirm_date')->nullable();
            $table->boolean('refunded')->nullable();
            $table->string('price')->nullable();
            $table->string('target_price')->nullable();
            $table->boolean('requested_already')->nullable();
            $table->string('status')->nullable();
            $table->text('comment')->nullable();
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
