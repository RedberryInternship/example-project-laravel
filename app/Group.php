<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /**
     * Laravel guarded attribute.
     * 
     * @var array
     */
    protected $guarded = [];

    /**
     * Relation to user.
     * 
     * @return User
     */
    public function user()
    {
        return $this -> belongsTo(User::class);
    }

    /**
     * Relation to chargers.
     * 
     * @return Collection
     */
    public function chargers()
    {
    	return $this -> belongsToMany(Charger::class);
    }
}
