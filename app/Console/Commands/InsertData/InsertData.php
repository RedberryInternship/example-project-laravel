<?php

namespace App\Console\Commands\InsertData;

use Illuminate\Console\Command;

class InsertData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:InsertData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute all commands';

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
        $this->info('Start inserting data');
        $this->call('command:insert_users');
        $this->call('command:insert_user_cards');
        $this->call('command:insert_tags');
        $this->call('command:insert_chargers');
        $this->call('command:insert_charger_connector_types');
        $this->call('command:insert_orders');
        $this->call('command:insert_payments');
        $this->call('command:insert_phone_codes');
        $this->info('Finished inserting Data');
    }
}
