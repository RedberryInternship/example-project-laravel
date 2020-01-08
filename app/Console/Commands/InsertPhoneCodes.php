<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\PhoneCode;

class InsertPhoneCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:insert_phone_codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parce phone codes json file and insert into espace database';

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
        $this->info('Execute insert phone codes');
        $path = public_path () . "/jsons/phone_codes.json";
        $json = json_decode(file_get_contents($path), true); 
        foreach ($json as $key => $value) {
            PhoneCode::create([
                'country_code' => $key,
                'phone_code'   => $value
            ]);
        }
        $this->info('Finished inserting phone codes');
    }
}
