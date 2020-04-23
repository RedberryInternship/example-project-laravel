<?php

namespace App\Console\Commands\ChargingSimulation;

use Illuminate\Console\Command;
use App\Facades\Charger;

class GetTransactionInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simulate:get-transaction-info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Transaction Info... -p [ transaction_id ]';

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
        $transaction_id = (int) $this -> ask('Give me transaction_id');
        
        
        dump(Charger::transactionInfo($transaction_id));

    }
}
