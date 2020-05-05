<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB :: table( 'configs' ) -> insert(
            [
                'initial_charging_price'    => 20,
                'next_charging_price'       => 10,
                'penalty_relief_minutes'    => 20,
                'penalty_price_per_minute'  => .5,
            ]
        );
    }
}
