<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalFieldsToNova extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('contract_file')->nullable()->after('name');
            $table->string('address')->nullable()->after('name');
            $table->string('bank_account')->nullable()->after('name');
            $table->date('contract_ended')->nullable()->after('name');
            $table->date('contract_started')->nullable()->after('name');
            $table->string('identification_code')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(
                [
                    'identification_code',
                    'contract_started',
                    'contract_ended',
                    'bank_account',
                    'address',
                    'contract_file',
                ]
            );
        });
    }
}
