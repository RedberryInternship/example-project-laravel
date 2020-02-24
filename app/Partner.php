<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    /**
     * Fillable fields.
     */
    protected $fillable = [
        'name',
        'image',
    ];
}
