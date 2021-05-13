<?php

namespace App\Console\Commands\CronJobs;

use App\Order;
use Illuminate\Console\Command;
use App\Library\Interactors\Firebase;
use App\Library\Interactors\ChargingUpdater;

/**
 * Cron Job which every minute watches orders
 * and updates their statuses.
 */
class UpdateActiveOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update-and-cache-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update and cache orders';

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
        $orders = Order::with('charger_connector_type.connector_type')->active()->get();

        $orders->each(function($order) {
            ChargingUpdater::updateAndCacheOrder($order);
            Firebase :: sendActiveOrders( $order -> user_id );
        });
    }
}
