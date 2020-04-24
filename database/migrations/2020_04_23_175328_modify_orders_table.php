<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('charger_transactions');

        Schema::table('orders', function( Blueprint $table ){
            $table -> dropColumn('refunded');
            $table -> dropColumn('finished');
            $table -> dropColumn('connector_type_id');
            $table -> dropColumn('charger_id');
            $table -> dropColumn('charger_type_id');

            $table -> unsignedBigInteger('charger_connector_type_id') -> after('charging_type_id');
            $table -> renameColumn('status', 'charging_status');
            $table -> json('charging_status_change_dates') -> after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function(Blueprint $table){
            $table -> boolean('refunded');
            $table -> boolean('finished');
            $table -> unsignedBigInteger('connector_type_id');
            $table -> unsignedBigInteger('charger_id');
            $table -> unsignedBigInteger('charger_type_id');

            $table -> dropColumn('charger_connector_type_id');
            $table -> dropColumn('charging_state_change_dates');

        });
    }
}
