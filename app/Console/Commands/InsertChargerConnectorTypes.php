<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Charger;
use App\ChargerConnectorType;
use App\ConnectorType;

class InsertChargerConnectorTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:insert_charger_connector_types';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parce ChargerConnectors Json file and insert into espace database';

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
        $path = public_path () . "/jsons/charger_connectors.json";
        $json = json_decode(file_get_contents($path), true);


        foreach($json as $connectors_arrays)
        {   
            foreach($connectors_arrays as $connector_array)
            {
                $connector_id        = $connector_array['connector_id'];
                $connector_type      = $connector_array['type'];
                $charger_old_id      = $connector_array['charger_id'];
                $charger_id          = null;

                if($charger_old_id != null)
                {
                    $charger             = Charger::where('old_id',$charger_old_id)->first();
                    $charger_id          = $charger -> id;
                }

                $connector_type_id   = null;

                if($connector_type != null)
                {
                    $connector_type      = ConnectorType::where('name',$connector_type)->first();
                    $connector_type_id   = $connector_type -> id;
                }

                $charger_connector_types = ChargerConnectorType::where('charger_id', $charger_id) -> update([
                    'connector_type_id'       => $connector_type_id
                ]);

                // foreach($charger_connector_types as $charger_connector_types)
                // {
                //     $charger_types_connector_type = ChargerConnectorType::update([
                //         'connector_type_id'       => $connector_type_id
                //     ]);
                // }
            }   
        }
    }
}
