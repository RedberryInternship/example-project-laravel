<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Library\Entities\Kilowatt as KilowattEntity;

class Kilowatt extends Model
{
    use KilowattEntity;

    /**
     * Laravel guarder attribute.
     */
    protected $guarded = [];
    
    /**
     * Laravel casts attribute.
     */
    protected $casts = [
        'consumed' => 'float'
    ];

    /**
     * Get Order, Kilowat belongs to.
     */
    public function order()
    {
        return $this -> belongsTo( Order :: class );
    }
}
