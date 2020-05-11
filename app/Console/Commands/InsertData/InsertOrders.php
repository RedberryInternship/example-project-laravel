<?php

namespace App\Console\Commands\InsertData;

use Illuminate\Console\Command;

use App\Enums\OrderStatus as OrderStatusEnum;
use App\Enums\ChargingType as ChargingTypeEnum;

use App\User;
use App\Charger;
use App\Order;
use App\ChargerConnectorType;

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
    protected $description = 'Parse Orders Json file and insert into espace database';

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
                $charger_transaction_id = $order_array['charger_transaction_id'];
                $state_change_dates     = [ 'INITIATED' => $order_array['confirm_date'], ];
                $price                  = $order_array['price'];
                $target_price           = $order_array['target_price'];
                $charger_old_id         = $order_array['charger_id'];
                $user_old_id            = $order_array['user_id'];
                $charging_type          = ChargingTypeEnum :: FULL_CHARGE;

                $user                   = User::where('old_id', $user_old_id)->first();

                $user_id                = '';

                if($user)
                {
                    $user_id            = $user -> id;
                }

                $charger    = Charger::where('old_id', $charger_old_id)->first();
                $charger_id = '';

                if($charger)
                {   
                    $charger_id             = $charger -> id;

                    $charger_connector_type = ChargerConnectorType::where('charger_id', $charger_id) -> first();

                    Order::create([
                        'old_id'                        => intval($old_id),
                        'user_id'                       => intval($user_id),
                        'charging_type'                 => $charging_type,
                        'charger_connector_type_id'     => $charger_connector_type -> id,
                        'charger_transaction_id'        => intval($charger_transaction_id),
                        'price'                         => $price,
                        'target_price'                  => $target_price,
                        'charging_status'               => OrderStatusEnum :: FINISHED,
                        'charging_status_change_dates'  => $state_change_dates,
                    ]);
                }
            }
        }
        $this->info('Finished inserting orders');
    }
}
