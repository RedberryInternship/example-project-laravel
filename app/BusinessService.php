<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessService extends Model
{
	protected $fillable = [
	    'user_id',
	    'title_en',
	    'title_ka',
	    'title_ru',
	    'description_en',
	    'description_ka',
	    'description_ru',
	    'image'
	];
	
}
