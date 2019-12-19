<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ComposerSelfUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'composer:self-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run composer self-update command in terminal';

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
        exec('composer self-update');
    }
}
