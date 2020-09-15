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
    protected $guarded = [];

    /**
     * Translatable Fields.
     */
    protected $translatable = ['name'];

    /**
     * Casts fields.
     * 
     * @var array $casts
     */
    protected $casts = [
        'contract_started' => 'date',
        'contract_ended' => 'date',
    ];
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
