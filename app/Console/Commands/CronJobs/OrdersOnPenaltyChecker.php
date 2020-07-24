<?php

namespace App\Console\Commands\CronJobs;

use App\Library\Interactors\CronJobs\OrdersOnPenaltyChecker as PenaltyChecker;
use Illuminate\Console\Command;

class OrdersOnPenaltyChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:check-orders-on-penalty';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'checks charged orders and if honor time is up they go into penalty mode.';

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
        PenaltyChecker :: check();
    }
}
