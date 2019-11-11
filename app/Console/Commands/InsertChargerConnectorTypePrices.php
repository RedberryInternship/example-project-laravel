<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ChargerConnectorType;

class InsertChargerConnectorTypePrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:insert_charger_connector_type_prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parce PricingList Json file and insert into espace database';

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
        $path = public_path () . "/jsons/pricing_list.json";
        $json = json_decode(file_get_contents($path), true);


        // foreach($json as $pricing_lists)
        // {
        //     foreach($pricing_lists as $pricing_list)
        //     {
        //         $
        //     }
        // }

        $charger_connector_types = ChargerConnectorType::orderBy('id','asc')->get();

        foreach ($charger_connector_types as $charger_connector_type)
        {
            $charger_connector_type_id = $charger_connector_type -> id; 
        }
    }   
}
