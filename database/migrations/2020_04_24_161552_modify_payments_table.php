<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema :: table( 'payments', function( Blueprint $table ){
            $table -> dropColumn([ 'active', 'date', 'status' ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema :: table( 'payments', function( Blueprint $table ){
            $table -> boolean( 'active' ) -> nullable();
            $table -> string ( 'date'   ) -> nullable();
            $table -> boolean( 'status' ) -> nullable();
        });
    }
}
