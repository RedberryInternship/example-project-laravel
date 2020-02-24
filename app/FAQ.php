<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
    /**
     * Table name.
     */
    protected $table = 'faqs';

    /**
     * Fillable fields.
     */
    protected $fillable = [
        'question',
        'answer',
    ];
}
