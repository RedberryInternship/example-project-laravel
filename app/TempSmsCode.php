<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempSmsCode extends Model
{
	/**
	 * Fillable Fields.
	 */
    protected $fillable = [
    	'phone_number',
    	'status',
    	'code'
	];

	/**
	 * Delete all code records by Phone Number
	 * 
	 * @param $phoneNumber
	 * 
	 * @return void
	 */
	public static function deleteCodesByPhoneNumber($phoneNumber)
	{
		self::where('phone_number', $phoneNumber) -> delete();
	}
}
