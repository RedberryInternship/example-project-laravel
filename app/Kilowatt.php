<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kilowatt extends Model
{
    protected $guarded = [];
    
    protected $casts = [
        'consumed' => 'object'
    ];

    public function chargerTransaction()
    {
        return $this -> belongsTo(ChargerTransaction::class, 'charger_transaction_id');
    }
}
