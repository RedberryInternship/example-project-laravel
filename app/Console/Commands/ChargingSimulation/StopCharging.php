<?php

namespace App\Console\Commands\ChargingSimulation;

use Illuminate\Console\Command;
use App\Facades\Charger;

class StopCharging extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simulate:stop-charging';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stop charging... - p [ charger_id, transaction_id ]';

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
        $transaction_id = (int) $this -> ask('Give me transaction_id');
        
        
        dump(Charger::stop($charger_id, $transaction_id));
    }
}
