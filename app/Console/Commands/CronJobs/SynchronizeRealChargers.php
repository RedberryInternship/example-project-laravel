<?php

namespace App\Console\Commands\CronJobs;

use App\Library\Interactors\CronJobs\RealChargersSyncer;
use Illuminate\Console\Command;

/**
 * Cron Job for synchronizing all the chargers from the Real Chargers Back
 * into our db. It includes chargers statuses: active, inactve, charging,
 * not working, latitude, longitude and so on... 
 */
class SynchronizeRealChargers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:synchronize-real-chargers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize real chargers to be up to date with real chargers data.';

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
        RealChargersSyncer :: syncAll();
    }
}
