<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name') -> unique();
            $table->timestamps();
        });
        DB::table('marks')->insert([
            ['name' => 'Audi'],
            ['name' => 'BMW'],
            ['name' => 'BMW Brilliance'],
            ['name' => 'Bolloré'],
            ['name' => 'BYD'],
            ['name' => 'Chery'],
            ['name' => 'Chevrolet'],
            ['name' => 'Citroën'],
            ['name' => 'COURB'],
            ['name' => 'ElectraMeccanica'],
            ['name' => 'Fiat'],
            ['name' => 'Ford'],
            ['name' => 'Girfalco'],
            ['name' => 'Honda'],
            ['name' => 'Hyundai'],
            ['name' => 'JAC Motors'],
            ['name' => 'Jaguar Land Rover'],
            ['name' => 'Kewet'],
            ['name' => 'Kia'],
            ['name' => 'Kyburz'],
            ['name' => 'Lightning'],
            ['name' => 'Mahindra'],
            ['name' => 'Mercedes-Benz'],
            ['name' => 'Micro Mobility Systems'],
            ['name' => 'Mitsubishi'],
            ['name' => 'Motores Limpios'],
            ['name' => 'MW Motors'],
            ['name' => 'NIO'],
            ['name' => 'Nissan'],
            ['name' => 'ECOmove'],
            ['name' => 'Peugeot'],
            ['name' => 'Rayttle'],
            ['name' => 'Renault'],
            ['name' => 'Smart'],
            ['name' => 'Sono Motors'],
            ['name' => 'Stevens'],
            ['name' => 'Tesla'],
            ['name' => 'Venturi'],
            ['name' => 'Volkswagen']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marks');
    }
}
