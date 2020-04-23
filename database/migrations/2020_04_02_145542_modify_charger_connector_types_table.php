<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyChargerConnectorTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
             
        Schema::table('charger_connector_types', function (Blueprint $table) {
            $table -> string('status') -> default('active') -> after('max_price');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::table('charger_connector_types', function (Blueprint $table) {
            $table->dropColumn('status');
        });

    }
}
