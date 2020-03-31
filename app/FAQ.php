<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class FAQ extends Model
{
    use HasTranslations;

    /**
     * Table name.
     */
    protected $table = 'faqs';

    /**
     * Translatable fields.
     */
    public $translatable = ['question', 'answer'];

    /**
     * Fillable fields.
     */
    protected $fillable = [
        'question',
        'answer',
    ];
}
