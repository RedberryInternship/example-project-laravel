<?php

namespace App\Console\Commands\ChargingSimulation;

use Illuminate\Console\Command;
use App\Facades\Simulator;

/**
 * Simulator class for activating charger simulator in order 
 * to test charger functionalities.
 * 
 * command: php artisan simulate:activate-charger
 * 
 * which means charger in plugged is to the power and is 
 * ready to charger the e-car.
 */
class ActivateCharger extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simulate:activate-charger';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Give charger simulation mode and make it up and running... -p [ charger_id ]';

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


        dump(
            Simulator::activateSimulatorMode($charger_id),
            Simulator::upAndRunning($charger_id),
        );

    }
}
