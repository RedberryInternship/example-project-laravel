<?php

namespace App\Console\Commands\InsertData;

use Illuminate\Console\Command;
use App\Tag;

class InsertTags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:insert_tags';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parce Categories Json file and insert into espace database';

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
        $this->info('Executing insert tags');
        $path = public_path () . "/jsons/categories.json";
        $json = json_decode(file_get_contents($path), true); 

        foreach($json as $tags_arrays)
        {   
            foreach($tags_arrays as $tag_array)
            {
                $old_id       = $tag_array['id'];

                $name_array   = array(
                                  'en' => urldecode($tag_array['name_en']),
                                  'ru' => urldecode($tag_array['name_ru']),
                                  'ka' => urldecode($tag_array['name'])
                                );
                
                $tag  = Tag::create([
                    'old_id'          => intval($old_id),
                    'name'            => $name_array,
                ]);
            }
        }
        $this->info('Finished inserting tags');
    }
}
