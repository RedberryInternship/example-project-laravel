<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ChargerTransaction extends Model
{
    /**
     * Laravel $guarded attribute
     * 
     * @var array
     */
    protected $guarded = [];

    /**
     * Kilowatt Relationship for storing 
     * charging kilowatt updates.
     * 
     * @return App\Kilowatt
     */
    public function kilowatt()
    {
        return $this -> hasOne(Kilowatt::class);
    }


    /**
     * Helper to create new kilowatt record
     * 
     * @param int|float $consumed
     * @return void
     */
    public function createKilowatt($consumed)
    {
        $this -> kilowatt()
            -> create([
                'consumed' => [
                    [
                        'date' => Carbon::now(),
                        'value' => $consumed,
                    ]
                ],
            ]);
    }

    /**
     * Helper to add new kilowatt updated values
     * into kilowatt consumed record as json.
     * 
     * @param int|float $value
     * @return void
     */
    public function addKilowatt($value)
    { 
        if(!$this -> kilowatt)
        {
            $this -> createKilowatt($value);
        }
        else
        {
            $consumed_kilowatt_data = $this -> kilowatt -> consumed;

            $updated_data = array_merge($consumed_kilowatt_data, [
                [
                    'date' => Carbon::now(),
                    'value' => $value,
                ],
            ]);

            $this -> kilowatt() -> update([
                'consumed' => $updated_data,
            ]);
        }
    }
}
