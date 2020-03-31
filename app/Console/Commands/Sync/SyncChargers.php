<?php

namespace App\Console\Commands\Sync;

use Illuminate\Console\Command;

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
    protected $description = 'Synchronize all the chargers from Misha\' back';

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
        $this -> info("Datvi");
    }
}
