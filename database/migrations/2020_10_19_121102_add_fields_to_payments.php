<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table -> unsignedBigInteger( 'user_id' ) -> nullable() -> after( 'order_id' );
            $table -> unsignedBigInteger( 'company_id' ) -> nullable() -> after( 'user_id' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table -> dropColumn([ 'user_id', 'company_id' ]);
        });
    }
}
