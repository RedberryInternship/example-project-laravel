<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class BusinessService extends Model
{
	use HasTranslations;

	/**
	 * Fillable fields.
	 */
	protected $fillable = [
	    'title',
	    'description',
	    'image',
	];

	/**
	 * Define translatable fields.
	 */
	public $translatable = [
      'title',
      'description',
	];

	/**
	 * Append attributes to return query rows.
	 */
	protected $appends = ['image_path'];

	/**
	 * Add Image Path attribute to model.
	 */
	public function getImagePathAttribute()
	{
		return url('/storage/' . $this -> image);
	}
}
