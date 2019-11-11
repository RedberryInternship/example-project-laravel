<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class InsertUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:insert_users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parce Users Json file and insert into espace database';

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
        $path = public_path () . "/jsons/users.json";
        $json = json_decode(file_get_contents($path), true); 

        foreach($json as $users_arrays)
        {   
            foreach($users_arrays as $user_array)
            {
                $old_id       = $user_array['id'];
                $email        = $user_array['email'];
                $first_name   = $user_array['first_name'];
                $last_name    = $user_array['last_name'];
                $phone_number = $user_array['phone_number'];
                $password     = $user_array['password'];
                $active       = 1;
                $verified     = 0;
                $role         = 1;

                $user   = User::create([
                    'old_id'                => intval($old_id),
                    'first_name'            => $first_name,
                    'last_name'             => $last_name,
                    'email'                 => $email,
                    'password'              => $password,
                    'phone_number'          => $phone_number,
                    'active'                => $active,
                    'verified'              => $verified,
                    'role'                  => $role
                ]);
            }
        }
    }
}
