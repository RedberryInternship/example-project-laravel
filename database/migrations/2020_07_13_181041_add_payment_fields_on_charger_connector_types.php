<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentFieldsOnChargerConnectorTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema :: table( 'charger_connector_types', function ( Blueprint $table ) {
            $table -> bigInteger( 'terminal_id' )->nullable();
            $table -> string    ( 'report'      )->default("");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema :: table( 'charger_connector_types', function ( Blueprint $table ) {
            $table -> dropColumn( 'terminal_id' );
            $table -> string    ( 'report'      );
        });
    }
}
