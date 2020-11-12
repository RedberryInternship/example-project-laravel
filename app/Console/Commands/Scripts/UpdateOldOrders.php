<?php

namespace App\Console\Commands\Scripts;

use Illuminate\Console\Command;

use App\Library\Interactors\Scripts\UpdateData;

class UpdateOldOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'script:update-old-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update boxwood orders for easy data access.';

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
        $this -> info('Started updating data, please be patient this may take a while...');
        UpdateData :: oldOrders();

        $this -> info('');
        $this -> info('Updating successfully completed!');
    }
}
