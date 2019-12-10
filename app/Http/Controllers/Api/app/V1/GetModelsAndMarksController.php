<?php

namespace App\Http\Controllers\Api\app\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CarModel;
use App\Mark;

class GetModelsAndMarksController extends Controller
{
    public function getModelsAndMarks()
    {
    	$marks 	= Mark::select('name','id') -> orderBy('name') -> withModelsOrNone() -> get();
    	return response() -> json(['marks_and_models' => $marks], 200);
    }
}
