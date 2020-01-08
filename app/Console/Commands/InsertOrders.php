<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\User;
use App\Charger;
use App\ChargerConnectorType;
use App\Order;
//use App\ChargerTypesConnectorType;

class InsertOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:insert_orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parce Orders Json file and insert into espace database';

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
        $this->info('Executing insert orders');
        $path = public_path () . "/jsons/orders.json";
        $json = json_decode(file_get_contents($path), true);
        
        foreach($json as $orders_arrays)
        {      
            foreach($orders_arrays as $order_array)
            {   
                $old_id                 = $order_array['id'];
                $status                 = $order_array['status'];
                if ($status == 7)
                {
                    $status = 0;
                }elseif ($status == 9) {
                    $status = 1;
                }
                $created_at             = $order_array['created_at'];
                $updated_at             = $order_array['updated_at'];
                $charger_transaction_id = $order_array['charger_transaction_id'];
                $confirm_date           = $order_array['confirm_date'];
                $confirmed              = $order_array['confirmed'];
                $must_pay               = $order_array['must_pay'];
                $payment_type           = $order_array['payment_type'];
                $price                  = $order_array['price'];
                $refunded               = $order_array['refunded'];
                $target_price           = $order_array['target_price'];
                $uuid                   = $order_array['uuid'];
                $charger_old_id         = $order_array['charger_id'];
                $user_old_id            = $order_array['user_id'];
                $requested_already      = $order_array['requested_already'];
                $charging_type          = 1;
                $finished               = 1;
                $charging_type_id       = 1;

                $user     = User::where('old_id', $user_old_id)->first();

                $user_id  = '';

                if($user)
                {
                    $user_id = $user -> id;
                }

                $charger  = Charger::where('old_id', $charger_old_id)->first();

                $charger_id = '';

                $charger_type_id    = null;
                $connector_type_id  = null;

                if($charger)
                {   
                    $charger_id     = $charger -> id;

                    $charger_connector_type = ChargerConnectorType::where('charger_id', $charger_id) -> first();

                    if($charger_connector_type)
                    {
                        $charger_type_id    = $charger_connector_type -> charger_type_id;
                        $connector_type_id  = $charger_connector_type -> connector_type_id;
                    }

                    $order =  Order::create([
                        'old_id'                 => intval($old_id),
                        'charger_id'             => intval($charger_id),
                        'user_id'                => intval($user_id),
                        'connector_type_id'      => $connector_type_id,
                        'charger_type_id'        => $charger_type_id,
                        'charging_type_id'       => $charging_type_id,
                        'finished'               => $finished,
                        'charger_transaction_id' => intval($charger_transaction_id),
                        'price'                  => $price,
                        'target_price'           => $target_price,
                        'confirmed'              => intval($confirmed),
                        'confirm_date'           => $confirm_date,
                        'refunded'               => intval($refunded),
                        'status'                 => $status,
                        'requested_already'      => intval($requested_already)
                    ]);
                }
            }
        }
        $this->info('Finished inserting orders');
    }
}
