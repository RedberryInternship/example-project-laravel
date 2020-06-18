<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCard extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this -> belongsTo('App\User');
    }

    public function isDefault()
    {
        return $this -> default;
    }

    public function setDefault()
    {
        $userId = $this -> user_id;
        User    :: find( $userId ) -> user_cards() -> update([ 'default' => false ]);
        $this   -> update([ 'default' => true ]);
    }

    public function deactivate()
    {
        $this -> active         = false;
        $this -> default        = false;
        $this -> prrn           = null;
        $this -> transaction_id = null;
        $this -> save();
    }
}
