<?php

namespace App\Console\Commands\CronJobs;

use App\Library\Interactors\CronJobs\OnHoldSwitcher;
use Illuminate\Console\Command;


//todo Vobi, please explain that class, Why use it? when does it run? and how?
class HoldSilentOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:hold-silent-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Every active order that has not received feedback for more than x minutes should be switched to ON_HOLD';

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
        OnHoldSwitcher :: execute();
    }
}
