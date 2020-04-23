<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class BusinessService extends Model
{
	use HasTranslations;

	protected $fillable = [
	    'user_id',
	    'title',
	    'description',
	    'image',
	];

	public $translatable = [
      'title',
      'description',
    ];
}
