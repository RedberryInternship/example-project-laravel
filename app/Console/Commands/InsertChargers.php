<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Charger;
use App\Tag;
use App\ChargerTag;
use App\ChargerConnectorType;

class InsertChargers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:insert_chargers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parce Chargers Json file and insert into espace database';

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
        $path = public_path () . "/jsons/chargers.json";
        $json = json_decode(file_get_contents($path), true);

        foreach($json as $chargers_arrays)
        {   
           
            foreach($chargers_arrays as $charger_array)
            {
                $old_id      = $charger_array['id'];
                $charger_id  = $charger_array['charger_id'];
                $code        = $charger_array['code'];
                $public      = $charger_array['paid'];
                $lat         = $charger_array['latitude'];               
                $lng         = $charger_array['longitude'];           
                $type        = $charger_array['type'];       
                $status      = $charger_array['status'];        
                $category_id = $charger_array['category_id'];
                $iban        = $charger_array['iban'];
                $tag_id      = null;

                if($category_id)
                {
                    $tag         = Tag::where('old_id',$category_id)->first();
                    $tag_id      = $tag -> id;
                }

                if($type == 0)
                {
                    $type = 1;

                }elseif($type == 1){
                    $type = 2;
                }

                if($status == -1)
                {
                    $status = 0;

                }elseif($status == 0){
                    $status = 1;
                }

                $charger_name = 'Charger'. ' ' .$charger_id . ' ' . $code;

                $name        = array(
                    'en'    => $charger_name,
                    'ru'    => $charger_name,
                    'ka'    => $charger_name
                );

                $location    = array(
                    'en'    => urldecode($charger_array['description_en']),
                    'ru'    => urldecode($charger_array['description_ru']),
                    'ka'    => urldecode($charger_array['description'])
                );

                $charger  = Charger::create([
                    'old_id'          => intval($old_id),
                    'name'            => $name,
                    'charger_id'      => intval($charger_id),
                    'code'            => $code,
                    'location'        => $location,                    
                    'public'          => $public,                    
                    'lat'             => $lat,                  
                    'lng'             => $lng,
                    'iban'            => $iban,
                    'active'          => $status

                ]);

                if($tag_id != null)
                {
                    $charger_tag = ChargerTag::create([
                        'charger_id'   => $charger -> id,
                        'tag_id'       => $tag_id
                    ]);
                }

                if($type)
                {
                    $charger_type = ChargerConnectorType::create([
                        'charger_id'        => $charger -> id,
                        'charger_type_id'   => $type,
                        'connector_type_id' => 9
                    ]);
                }
            }   
        }
    }
}
