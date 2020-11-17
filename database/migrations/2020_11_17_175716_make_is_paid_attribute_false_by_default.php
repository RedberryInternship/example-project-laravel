<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeIsPaidAttributeFalseByDefault extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema :: table( 'chargers', function( Blueprint $table ) {
            $table -> boolean( 'is_paid' ) -> default( false ) -> change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema :: table( 'chargers', function( Blueprint $table ) {
            $table -> boolean( 'is_paid' ) -> default( true ) -> change();
        });
    }
}
