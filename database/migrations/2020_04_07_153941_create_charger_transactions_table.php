<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChargerTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charger_transactions', function (Blueprint $table) {
            $table -> bigIncrements('id');
            $table -> unsignedBigInteger('charger_id');
            $table -> unsignedBigInteger('connector_type_id');
            $table -> unsignedBigInteger('m_connector_type_id');
            $table -> string('transactionID');
            $table -> string('status') -> default('INITIATED');
            $table -> timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('charger_transactions');
    }
}
