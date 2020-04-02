<?php

namespace App\Console\Commands\Sync;

use Illuminate\Console\Command;
use App\Facades\ChargerSyncer;

class SyncSingleCharger extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:single';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync specific charger with [Charger -> charger_id]';

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
        $charger_id = $this -> ask('Gimme charger_id[ Charger -> charger_id] to update the charger.');
        
        ChargerSyncer::insertOrUpdateOne($charger_id);

        $this -> info('Charger successfully updated!');
    }
}
