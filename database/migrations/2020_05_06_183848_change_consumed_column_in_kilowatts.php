<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeConsumedColumnInKilowatts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kilowatts', function (Blueprint $table) {
          $table -> string( 'consumed' ) -> change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kilowatts', function (Blueprint $table) {
            $table -> json( 'consumed' ) -> change();
        });
    }
}
