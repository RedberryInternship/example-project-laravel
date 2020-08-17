<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMinMaxPricesToChargerConnectorTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        #TODO: Needs Refactor
        # Giuna # Why ?

        if( app() -> runningUnitTests() )
        {
            Schema::table('charger_connector_types', function (Blueprint $table) {
                $table->float('min_price', 5, 2) -> default(0);
                $table->float('max_price', 5, 2) -> default(0);
            });
        }
        else 
        {
            Schema::table('charger_connector_types', function (Blueprint $table) {
                $table->float('min_price', 5, 2);
                $table->float('max_price', 5, 2);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('charger_connector_types', function (Blueprint $table) {
            $table->dropColumn('min_price');
            $table->dropColumn('max_price');
        });
    }
}
