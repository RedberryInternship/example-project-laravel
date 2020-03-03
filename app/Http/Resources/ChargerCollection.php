<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ChargerCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {   
        return parent::toArray($request);
    }

    /**
     * Add more attributes to ChargerCollection.
     * @param Request $request
     */
    public function with($request)
    {
        return [
            'current_hour' => Carbon::now() -> format('H')
        ];
    }
}
