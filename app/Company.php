<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Company extends Model
{
    use HasTranslations;

    /**
     * Fillable Fields.
     */
    protected $fillable = ['name'];

    /**
     * Translatable Fields.
     */
    protected $translatable = ['name'];

    /**
     * Get Company Users.
     */
    public function users()
    {
        return $this -> hasMany(User::class);
    }

    /**
     * Get Company Chargers.
     */
    public function chargers()
    {
        return $this -> hasMany(Charger::class);
    }
}
