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
        #0001
        #0010
        $liparit = factory( User :: class ) -> create(
            [
                'phone_number'  => '+995591935080',
                'first_name'    => 'Liparit',
                'last_name'     => 'Bagvashi',
                'email'         => 'liparit@mail.ru',
                'password'      => bcrypt( 'rati1021' ),
                'active'        => 1,
                'verified'      => 1,
            ]
        );

        factory( UserCard :: class ) -> create(
            [
                'user_id'     => $liparit -> id,
                'card_holder' => 'Liparit Bagvashi',
                'masked_pan'  => '411634xxxxxx9100',
            ]
        );
        
        #0009
        $espace = factory( User :: class ) -> create(
            [
                'phone_number'  => '+995598301266',
                'first_name'    => 'Espace',
                'last_name'     => 'Ltd',
                'email'         => 'admin@espace.ge',
                'password'      => bcrypt( 'espace' ),
                'active'        => 1,
                'verified'      => 1,
            ]
        );

        factory( UserCard :: class ) -> create(
            [
                'user_id'     => $espace -> id,
                'card_holder' => 'Espace LTD',
                'masked_pan'  => '411634xxxxxx9100',
            ]
        );
        
        #0064
        #0021
        $mako = factory( User :: class ) -> create(
            [
                'phone_number'  => '+995598511266',
                'first_name'    => 'Mako',
                'last_name'     => 'Ko',
                'email'         => 'mako@mako.ge',
                'password'      => bcrypt( 'maaako' ),
                'active'        => 1,
                'verified'      => 1,
            ]
        );
        
        factory( UserCard :: class ) -> create(
            [
                'user_id'     => $mako -> id,
                'card_holder' => 'Mako Ko',
                'masked_pan'  => '418220xxxxxx9000',
            ]
        );
        
        factory( UserCard :: class ) -> create(
            [
                'user_id'     => $mako -> id,
                'card_holder' => 'Mako Ko',
                'masked_pan'  => '418220xxxxxx9100',
            ]
        );
        
        #0008
        $mari = factory( User :: class ) -> create(
            [
                'phone_number'  => '+995598511111',
                'first_name'    => 'Giga',
                'last_name'     => 'Sha',
                'email'         => 'mari@ami.ge',
                'password'      => bcrypt( 'turboo' ),
                'active'        => 1,
                'verified'      => 1,
            ]
        );

        factory( UserCard :: class ) -> create(
            [
                'user_id'     => $mari -> id,
                'card_holder' => 'Giga Sha',
                'masked_pan'  => '411620xxxxxx0100',
            ]
        );
        
        #0028
        $giuna = factory( User :: class ) -> create(
            [
                'phone_number'  => '+995591935081',
                'first_name'    => 'Murvan',
                'last_name'     => 'Yru',
                'email'         => 'murvan@egchemis.yru',
                'password'      => bcrypt( 'rati1021' ),
                'active'        => 1,
                'verified'      => 1,
            ]
        );

        factory( UserCard :: class ) -> create(
            [
                'user_id'     => $giuna -> id,
                'card_holder' => 'Murvan Yru',
                'masked_pan'  => '400620xxxxxx0100',
            ]
        );

        #0022
        $inola = factory( User :: class ) -> create(
            [
                'phone_number'  => '+995598003780',
                'first_name'    => 'Inola',
                'last_name'     => 'Porchkhidze',
                'email'         => 'inola@tester.mriskhane',
                'password'      => bcrypt( 'barbarestan' ),
                'active'        => 1,
                'verified'      => 1,
            ]
        );

        factory( UserCard :: class ) -> create(
            [
                'user_id'     => $inola -> id,
                'card_holder' => 'Inolla Porchkhidze',
                'masked_pan'  => '400000xxxxxx0100',
            ]
        );

        #5004
        $nankuka = factory( User :: class ) -> create(
            [
                'phone_number'  => '+995598003300',
                'first_name'    => 'Nanuka',
                'last_name'     => 'Zhorzholiani',
                'email'         => 'nanukas@show.ge',
                'password'      => bcrypt( 'nanu-kaa!' ),
                'active'        => 1,
                'verified'      => 1,
            ]
        );

        factory( UserCard :: class ) -> create(
            [
                'user_id'     => $nankuka -> id,
                'card_holder' => 'Nanuka Zhorzholiani',
                'masked_pan'  => '400000xxxxxx9988',
            ]
        );
    }
}
