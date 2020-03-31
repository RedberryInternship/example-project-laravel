<?php

use Illuminate\Database\Seeder;
use DB;

class Users extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [   
                'role_id'            => 2,
                'first_name'         => 'Admin',
                'last_name'          => 'Espace',
                'phone_number'       => '111',
                'email'              => 'admin@espace.ge',
                'active'             =>  true,
                'verified'           =>  true,
                'password'           =>  bcrypt('admin2000'),
            ],
            [
                'role_id'            => 4,
                'first_name'         => 'Payment',
                'last_name'          => 'Espace',
                'phone_number'       => '222',
                'email'              => 'payment@espace.ge',
                'active'             =>  true,
                'verified'           =>  true,
                'password'           =>  bcrypt('M9QwdZh1i4MHYV5v'),
            ]
        ]);
    }
}
