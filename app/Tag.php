<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Tag extends Model
{
	use HasTranslations;

	public $translatable = ['name'];

    protected $fillable = [
        'name',
        'old_id'
    ];
}
