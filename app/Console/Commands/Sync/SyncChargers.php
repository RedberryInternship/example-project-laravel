<?php

namespace App\Console\Commands\Sync;

use Illuminate\Console\Command;
use App\Facades\ChargerSyncer;

class SyncChargers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:chargers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize all the chargers from Misha\'s back';

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
        $this -> info("Started charger synchronizing from Misha's back...");
        
        ChargerSyncer::insertOrUpdate();

        $this -> info('');
        $this -> info("Synchronizing completed successfully!");
    }
}
