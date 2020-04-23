<?php

namespace App\Console\Commands\ChargingSimulation;

use Illuminate\Console\Command;
use App\Facades\Charger;

class StartCharging extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simulate:start-charging';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start charging in simulation mode... -p [charger_id]';

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
        
        $charger_id = (int) $this -> ask('Give me charger_id');
        $connector_id = (int) $this -> ask('Give me connector_id');
                
        dump(Charger::start($charger_id, $connector_id));

    }
}
