<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateChargerConnectorTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('charger_connector_types', function (Blueprint $table){
            $table -> unsignedInteger('m_connector_type_id') -> nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('charger_connector_types', function (Blueprint $table){
            $table -> dropColumn('m_connector_type_id');
        });
    }
}
