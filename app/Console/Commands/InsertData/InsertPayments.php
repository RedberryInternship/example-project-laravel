<?php

namespace App\Console\Commands\InsertData;

use Illuminate\Console\Command;
use App\Order;
use App\UserCard;
use App\Payment;

class InsertPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:insert_payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parce Payements Json file and insert into espace database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Executing insert payments');
        $path = public_path () . "/jsons/payment.json";
        $json = json_decode(file_get_contents($path), true);
        foreach($json as $payments_arrays)
        {      
            foreach($payments_arrays as $payments_array)
            {
                $old_id             = $payments_array['id'];
                $status             = $payments_array['status'];
                $created_at         = $payments_array['created_at'];
                $updated_at         = $payments_array['updated_at'];
                $active             = $payments_array['active'];
                $confirmed          = $payments_array['confirmed'];
                $confirm_date       = $payments_array['confirm_date'];
                $date               = $payments_array['date'];
                $price              = $payments_array['price'];
                $prrn               = $payments_array['prrn'];
                $trx_id             = $payments_array['trx_id'];
                $old_order_id       = $payments_array['order_id'];
                $old_credit_card_id = $payments_array['credit_card_id'];
                
                $order    = Order::where('old_id', $old_order_id) -> first(); 
                $order_id       = null; 
                $user_card_id   = null;  
                if($order)
                {
                    $order_id       = $order->id;
                    $user_card      = UserCard::where('old_id', $old_credit_card_id) -> first();
                    if($user_card)
                    {
                        $user_card_id = $user_card -> id;
                        $payment   = Payment::create([
                            'old_id'       => $old_id,
                            'order_id'     => $order_id,
                            'status'       => $status,
                            'created_at'   => $created_at,
                            'updated_at'   => $updated_at,
                            'active'       => $active,
                            'confirmed'    => intval($confirmed),
                            'confirm_date' => $confirm_date,
                            'date'         => $date,
                            'price'        => $price,
                            'prrn'         => $prrn,
                            'trx_id'       => $trx_id,
                            'user_card_id' => $user_card_id
                        ]); 
                    }
                }
            }
        }
        $this->info('Finished inserting payments');
    }
}
