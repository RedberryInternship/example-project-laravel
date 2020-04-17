<?php

namespace App\Console\Commands\InsertData;

use App\ChargingPrice;
use App\FastChargingPrice;
use App\ChargerConnectorType;
use Illuminate\Console\Command;

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
        $chargerConnectorTypes = ChargerConnectorType::all();

        foreach ($chargerConnectorTypes as $chargerConnectorType)
        {
            if ($chargerConnectorType == 1) // Level 2
            {
                $prices = [
                    ['min_kwt' => 0, 'max_kwt' => 5, 'price' => 0],
                    ['min_kwt' => 6, 'max_kwt' => 10, 'price' => 10],
                    ['min_kwt' => 11, 'max_kwt' => 1000000, 'price' => 20],
                ];

                foreach ($prices as $price)
                {
                    ChargingPrice::create([
                        'charger_connector_type_id' => $chargerConnectorType -> id,
                        'min_kwt'                   => $price['min_kwt'],
                        'max_kwt'                   => $price['max_kwt'],
                        'start_time'                => 0,
                        'end_time'                  => 24,
                        'price'                     => $price['price']
                    ]);
                }
            }
            else
            {
                $prices = [
                    ['start_minutes' => 0, 'end_minutes' => 20, 'price' => 5],
                    ['start_minutes' => 21, 'end_minutes' => 40, 'price' => 10],
                    ['start_minutes' => 41, 'end_minutes' => 60, 'price' => 20],
                ];

                foreach ($prices as $price)
                {
                    FastChargingPrice::create([
                        'charger_connector_type_id' => $chargerConnectorType -> id,
                        'start_minutes'             => $price['start_minutes'],
                        'end_minutes'               => $price['end_minutes'],
                        'price'                     => $price['price']
                    ]);
                }
            }
        }
    }   
}
