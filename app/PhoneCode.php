<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhoneCode extends Model
{
    protected $fillable = [
        'country_code',
        'phone_code'
    ];
}
