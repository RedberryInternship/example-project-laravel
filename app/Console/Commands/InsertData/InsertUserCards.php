<?php

namespace App\Console\Commands\InsertData;

use Illuminate\Console\Command;
use App\User;
use App\UserCard;

class InsertUserCards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:insert_user_cards';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parce UserCards Json file and insert into espace database';

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
        $this->info('Executing insert user cards');
        $path = public_path () . "/jsons/credit_cards.json";
        $json = json_decode(file_get_contents($path), true);

        foreach($json as $user_card_arrays)
        {
            foreach($user_card_arrays as $user_card_array)
            {
                $old_id         = $user_card_array['id'];
                $old_user       = intval($user_card_array['user_id']);

                $user_id        = null;

                if($old_user != null)
                {
                    $user           = User::where('old_id',$old_user)->first();
                    $user_id        = $user -> id;
                }

                $created_at     = $user_card_array['created_at'];
                $masked_pan     = $user_card_array['masked_pan'];
                $order_index    = $user_card_array['order_index'];
                $transaction_id = $user_card_array['trx_id'];
                $card_holder    = $user_card_array['card_holder'];

                $user_card  = UserCard::create([
                    'old_id'         => $old_id,
                    'masked_pan'     => $masked_pan,
                    // 'order_index'    => $order_index,
                    'transaction_id' => $transaction_id,
                    'card_holder'    => $card_holder,
                    'user_id'        => $user_id,
                    'user_old_id'    => $old_user
                ]);
            }
        }
        $this->info('Finished inserting user cards');
    }
}
