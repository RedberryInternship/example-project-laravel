<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChargerTag extends Model
{
    protected $fillable = [
        'charger_id',
        'tag_id'
    ];

    public function chargers()
    {
        return $this -> belongsTo('App/Charger','charger_tags');
    }

    public function tag()
    {
    	return $this -> belongsTo('App\Tag');
    }
}
