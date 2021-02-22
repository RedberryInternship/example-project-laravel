<?php

namespace App\Console\Commands\Scripts;

use Illuminate\Console\Command;

use App\Library\Interactors\Scripts\UpdateData;

/**
 * One time script that caches newly created relationships
 * in the old records.
 */
class CacheOrdersAndPaymentsForeignKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'script:cache-orders-and-payments-foreign-keys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache orders and payments non-immediate relation foreign keys...';

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
        $this -> info('Starting caching foreign keys...');

        UpdateData :: cacheOrdersAndPaymentsForeignKeys();
        $this -> info('');
        $this -> info('Successfully completed caching...');
    }
}
