<?php

namespace App\Console\Commands\CronJobs;

use App\Library\Interactors\CronJobs\OrdersOnPenaltyChecker as PenaltyChecker;
use Illuminate\Console\Command;

/**
 * Cron Job for checking charging process, if their penalty
 * relief period is up, and if so, we need to make those
 * kind of charging process charging status tag as "ON_FINE".
 */
class OrdersOnPenaltyChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:check-orders-on-penalty';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'checks charged orders and if honorary period is up they go into penalty mode.';

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
        PenaltyChecker :: check();
    }
}
