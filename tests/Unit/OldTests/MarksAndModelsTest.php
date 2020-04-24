<?php

namespace Tests\Unit\OldTests;

use Tests\TestCase;
use App\CarModel;
class MarksAndModelsTest extends TestCase
{
    public function testMarks()
    {

        $models = CarModel::with('mark') -> get();

        $array  =  [];

        foreach($models as $model)
        {
            array_push($array,$model -> mark -> name);
        }

        $a = array_unique($array);

    	$responseJson = $this -> response = $this->json('GET', "/api/app/V1/get-models-and-marks");

    	$this -> assertEquals(count($responseJson -> original),count($a));
       	
    }

    public function TestModels()
    {
        $models = CarModel::all();

        $responseJson = $this -> response = $this->json('GET', "/api/app/V1/get-models-and-marks");

        $marks = $responseJson -> original['marks_and_models'];

        $models_ar = [];

        foreach($marks as $mark)
        {
            foreach ($mark['models'] as $value)
            {   
                array_push($models_ar,$value['name']);
            }
        }

        $this -> assertEquals(count($models),count($models_ar));

    }
}
