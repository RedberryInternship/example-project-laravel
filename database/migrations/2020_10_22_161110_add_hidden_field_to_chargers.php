<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHiddenFieldToChargers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chargers', function (Blueprint $table) {
            $table -> boolean( 'hidden' ) -> default( false ) -> after( 'active' );
        });
        
        Schema::table('chargers', function (Blueprint $table) {
            $table -> dropColumn('active');
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
            $table -> boolean('active') -> after('hidden');
            $table -> dropColumn( 'hidden' );
        });
    }
}
