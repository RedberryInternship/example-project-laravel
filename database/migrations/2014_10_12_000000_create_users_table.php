<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('old_id')->nullable();
            $table->integer('role')->default(1);
            $table->string('phone_number');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->boolean('active')->default(false);
            $table->boolean('verified')->default(false);
            $table->integer('car_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('users')->insert([
            [   
                'role'               => 2,
                'first_name'         => 'Admin',
                'last_name'          => 'Espace',
                'phone_number'       => '111',
                'email'              => 'admin@espace.ge',
                'active'             =>  true,
                'verified'           =>  true,
                'password'           =>  bcrypt('admin2000'),
            ]
        ]);
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
