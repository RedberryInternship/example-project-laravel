<?php

namespace App\Console\Commands\CronJobs;

use Illuminate\Console\Command;

use App\Library\Interactors\CronJobs\NotConfirmedOrdersChecker as NotConfirmedChecker;

class NotConfirmedOrdersChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:not-confirmed-orders-checker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check not confirmed orders every x minutes and if after y minutes order is still not confirmed change it to on hold status.';

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
        NotConfirmedChecker :: check();
    }
}
