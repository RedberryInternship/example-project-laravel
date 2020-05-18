<?php

namespace App\Http\Controllers\Api\app\V1;

use App\Http\Resources\CarCollection;
use App\Http\Controllers\Controller;
use App\Mark;

class GetModelsAndMarksController extends Controller
{
    public function getModelsAndMarks()
    {
    	return new CarCollection(Mark::select('name','id') -> orderBy('name') -> withModelsOrNone() -> get());
    }
    	
}
