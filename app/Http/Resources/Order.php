<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Library\Presenters\ChargingProcess;

class Order extends JsonResource
{
    /**
     * Passed order instance.
     * 
     * @var \App\Order $order
     */
    private $order;

    /**
     * Set without wrapping property
     * onto resource.
     * However, this won't work on collections.
     */
    public function __construct( $resource )
    {
        $this -> order = $resource;
        parent :: __construct( $resource );
        static :: withoutWrapping();
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {        
        return ChargingProcess :: build( $this -> order ) -> resolve();
    }

}
