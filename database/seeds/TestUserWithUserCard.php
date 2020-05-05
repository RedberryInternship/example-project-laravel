<?php

use Illuminate\Database\Seeder;

use App\User;
use App\UserCard;

class TestUserWithUserCard extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = factory( User :: class ) -> create(
            [
                'phone_number'  => '+995591935080',
                'first_name'    => 'Liparit',
                'last_name'     => 'Bagvashi',
                'email'         => 'liparit@mail.ru',
                'password'      => bcrypt( 'rati1021' ),
                'active'        => 1,
            ]
        );

        factory( UserCard :: class ) -> create(
            [
                'user_id'     => $user -> id,
                'card_holder' => 'Liparit Bagvashi',
                'masked_pan'  => '411634xxxxxx9100',
            ]
        );
    }
}
