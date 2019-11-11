<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChargerTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charger_tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('charger_id');
            $table->unsignedBigInteger('tag_id')->nullable();
            $table->timestamps();
            $table->foreign('charger_id')->references('id')->on('chargers')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            $table->unique(['charger_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('charger_tags');
    }
}
