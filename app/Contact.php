<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    /**
     * Fillable Fields.
     */
    public $fillable = [
        'address',
        'phone',
        'email',
        'fb_page',
        'fb_page_url',
        'web_page',
        'web_page_url',
    ];
}
