<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AttachChargerToCompanyNotUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chargers', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->unsignedInteger('company_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chargers', function (Blueprint $table) {
            $table->dropColumn('company_id');
            $table->unsignedInteger('user_id')->nullable();
        });
    }
}
