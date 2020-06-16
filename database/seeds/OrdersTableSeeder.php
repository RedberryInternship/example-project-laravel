<?php

use App\Order;
use App\Payment;
use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Order::class, 1000)
            -> create([
                'charger_connector_type_id' => 1086
            ])
            -> each(function($order) {
                factory(Payment::class, rand(1, 2)) -> create([
                    'order_id' => $order -> id
                ]);
            });
    }
}
