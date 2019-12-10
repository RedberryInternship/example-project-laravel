<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('old_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->boolean('status')->nullable();
            $table->boolean('active')->nullable();
            $table->boolean('confirmed')->nullable();
            $table->string('confirm_date')->nullable();
            $table->string('date')->nullable();
            $table->string('price')->nullable();
            $table->string('prrn')->nullable();
            $table->string('trx_id')->nullable();
            $table->integer('user_card_id')->nullable();
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
        Schema::dropIfExists('payments');
    }
}
