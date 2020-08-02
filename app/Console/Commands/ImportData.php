<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Library\Interactors\DataImports\BoxwoodDataImporter;
use App\Library\Interactors\DataImports\ImportBeforeBoxwood;
use App\Library\Interactors\DataImports\ImportAfterBoxwood;

class ImportData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import all the necessary data, including: boxwood data, default users, phone codes etc...';

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
        $this -> info( 'Be patient, setting up Espace initial data...' );
        $bar = $this -> output -> createProgressBar( 3 );
        $this -> info('');
        
        ImportBeforeBoxwood :: execute(); $bar -> advance();
        BoxwoodDataImporter :: import();  $bar -> advance();
        ImportAfterBoxwood  :: execute(); $bar -> advance();

        $this -> info(''); $this -> info('');
        $this -> info( 'Congrats! data is set. Happy charging ^_^' );
    }
}
