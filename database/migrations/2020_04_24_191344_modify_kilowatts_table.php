<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyKilowattsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kilowatts', function (Blueprint $table) {
            $table -> renameColumn( 'charger_transaction_id', 'order_id' );
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
            $table -> renameColumn( 'order_id', 'charger_transaction_id' );
        });
    }
}
