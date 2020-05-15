<?php

namespace App\Http\Controllers\Api\app\V1;

use App\Http\Resources\PhoneCodeCollection;
use App\Http\Controllers\Controller;
use App\PhoneCode;

class PhoneCodesController extends Controller
{
    public function getPhoneCodes()
    {
    	return new PhoneCodeCollection(PhoneCode::select('id','country_code','phone_code') -> orderBy('country_code') -> get());
    }
}
