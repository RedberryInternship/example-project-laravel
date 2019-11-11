<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChargersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chargers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('old_id')->nullable();
            $table->json('name')->nullable();
            $table->integer('charger_id');
            $table->string('code')->nullable();
            $table->json('description')->nullable();
            $table->integer('user_id')->nullable();
            $table->json('location')->nullable();
            $table->boolean('public')->default(false);
            $table->boolean('active')->default(false);
            $table->string('lat');
            $table->string('lng');
            $table->string('iban')->nullable();
            $table->integer('charger_group_id') -> nullable();
            $table->dateTime('last_update')->nullable();
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
        Schema::dropIfExists('chargers');
    }
}
