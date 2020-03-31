<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetPublicFieldAsNullableInChargers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chargers', function (Blueprint $table) {
            $table -> boolean('public') -> nullable(true) -> change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chargers', function (Blueprint $table) {
            $table -> boolean('public') -> nullable(false) -> change();
        });
    }
}
