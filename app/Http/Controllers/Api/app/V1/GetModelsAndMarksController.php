<?php

namespace App\Http\Controllers\Api\app\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CarModel;
use App\Mark;
use App\Http\Resources\CarCollection;

class GetModelsAndMarksController extends Controller
{
    public function getModelsAndMarks()
    {
    	return new CarCollection(Mark::select('name','id') -> orderBy('name') -> withModelsOrNone() -> get());
    }
    	
}
