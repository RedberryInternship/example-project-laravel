<?php

namespace App\Console\Commands\CronJobs;

use Illuminate\Console\Command;

use App\Library\Interactors\CronJobs\PreChargedOrdersChecker;

/**
 * Cron Job which take cares off the situation in which
 * already charged car could be plugged into charger
 * and start charging.
 * In that case we have to identify those kind of cases
 * and stop those charging processes.
 */
class CheckPrechargedOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:pre-charged-orders-checker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if car is already charged and if so stop the transaction.';

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
        PreChargedOrdersChecker :: check();
    }
}
