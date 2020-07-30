<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TerminalIdAndReportNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema :: table( 'charger_connector_types', function( Blueprint $table ) {
            $table -> bigInteger( 'terminal_id' ) -> nullable() -> change();
            $table -> bigInteger( 'report'      ) -> nullable() -> change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema :: table( 'charger_connector_types', function( Blueprint $table ) {
            $table -> bigInteger( 'terminal_id' ) -> change();
            $table -> bigInteger( 'report'      ) -> change();
        });
    }
}
