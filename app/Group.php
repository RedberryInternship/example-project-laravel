<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'name',
        'user_id'
    ];

    public function user()
    {
        return $this -> belongsTo(User::class);
    }

    public function chargers()
    {
    	return $this -> belongsToMany(Charger::class);
    }

    public function scopeWithChargers($query)
    {
        return $query -> with(['chargers' => function($q) {
            return $q -> withAllAttributes();
        }]);
    }
}
