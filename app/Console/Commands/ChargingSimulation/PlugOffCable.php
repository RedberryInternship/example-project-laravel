<?php

namespace App\Console\Commands\ChargingSimulation;

use Illuminate\Console\Command;
use App\Facades\Simulator;

/**
 * Simulator class for simulating plugging of connector from
 * the charging car or the charger itself.
 * 
 * command: php artisan simulate:plug-off-cable
 */
class PlugOffCable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simulate:plug-off-cable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Plug the charging cable off the charger... - p [ charger_id ]';

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
        Simulator::plugOffCable($charger_id),
      );
    }
}
