<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempSmsCode extends Model
{
    protected $fillable = [
    	'phone_number',
    	'code',
    	'status'
    ];
}
