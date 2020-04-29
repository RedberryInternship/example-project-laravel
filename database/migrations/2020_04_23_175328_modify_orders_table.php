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
            
            $table -> dropColumn([ 
                'refunded', 
                'finished', 
                'connector_type_id', 
                'connector_type_id',
                'charger_type_id',
                'charger_id',
                'connector_type_id',
                ]
            );
        });

        Schema::table('orders', function( Blueprint $table ){
            $table -> renameColumn('status', 'charging_status');
        });

        Schema::table('orders', function( Blueprint $table ){
            $table -> unsignedBigInteger('charger_connector_type_id') -> default(0) -> after('charging_type_id');
            $table -> json('charging_status_change_dates') -> nullable( true ) -> after('status');
            $table -> unsignedBigInteger('old_id') -> nullable( true ) -> change();
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
            $table -> unsignedBigInteger('old_id') -> nullable( false ) -> change();

            $table -> dropColumn('charger_connector_type_id');
            $table -> dropColumn('charging_state_change_dates');

        });
    }
}
