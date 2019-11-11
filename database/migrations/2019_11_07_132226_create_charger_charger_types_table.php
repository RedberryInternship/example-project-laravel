<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChargerChargerTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charger_charger_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('charger_id')->unsigned();
            $table->unsignedBigInteger('charger_type_id')->unsigned();
            $table->foreign('charger_id')->references('id')->on('chargers')->onDelete('cascade');
            $table->foreign('charger_type_id')->references('id')->on('charger_types')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['charger_id', 'charger_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('charger_charger_types');
    }
}
