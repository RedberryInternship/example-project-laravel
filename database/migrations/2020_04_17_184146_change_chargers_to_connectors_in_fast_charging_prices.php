<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeChargersToConnectorsInFastChargingPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fast_charging_prices', function (Blueprint $table) {
            $table -> dropColumn('charger_id');
        });

        Schema::table('fast_charging_prices', function (Blueprint $table){
            if( app() -> runningUnitTests() )
            {
                $table -> integer('charger_connector_type_id') -> nullable( true ) -> unsigned();
            }
            else
            {
                $table -> integer('charger_connector_type_id') -> unsigned();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fast_charging_prices', function (Blueprint $table) {
            $table->integer('charger_id')->unsigned();
            $table->dropColumn('charger_connector_type_id');            
        });
    }
}
