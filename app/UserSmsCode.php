<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSmsCode extends Model
{
    protected $fillable = [
        'phone',
        'code',
        'tries'
    ];
}
