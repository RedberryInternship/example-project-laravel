<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table -> unsignedBigInteger( 'company_id' ) -> nullable( true ) -> after( 'user_card_id' );
            $table -> float( 'consumed_kilowatts' ) -> nullable( true ) -> after( 'charge_power' );
            $table -> dropColumn( 'expense' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table -> dropColumn([ 'company_id', 'consumed_kilowatts' ]);
            $table -> string( 'expense' );
        });
    }
}
