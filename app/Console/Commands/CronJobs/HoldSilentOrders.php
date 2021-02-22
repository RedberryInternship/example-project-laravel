<?php

namespace App\Console\Commands\CronJobs;

use App\Library\Interactors\CronJobs\OnHoldSwitcher;
use Illuminate\Console\Command;


/**
 * Cron Job for the charging process on which we don't 
 * receive information anymore. 
 * So, we need to change charging status of those into "ON_HOLD",
 * so that we can notify user that there is some problem going on...
 */
class HoldSilentOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:hold-silent-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Every active order that has not received feedback for more than x minutes should be switched to ON_HOLD';

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
        OnHoldSwitcher :: execute();
    }
}
