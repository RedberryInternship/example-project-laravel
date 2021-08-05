<?php

namespace App\Console\Commands\CronJobs;

use App\Library\Interactors\CronJobs\UnhandledChargingPowerChecker;

use Illuminate\Console\Command;

class CheckUnhandledChargingPowers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:check-unhandled-charging-powers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check charging powers that are left with end_at = null';

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
        UnhandledChargingPowerChecker :: checkAll();
    }
}
