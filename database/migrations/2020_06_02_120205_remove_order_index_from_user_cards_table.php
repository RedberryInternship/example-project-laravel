<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveOrderIndexFromUserCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_cards', function (Blueprint $table) {
            $table -> dropColumn( 'order_index' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_cards', function (Blueprint $table) {
            $table -> integer( 'order_index' ) -> nullable() -> after( 'masked_pan' );
        });
    }
}
